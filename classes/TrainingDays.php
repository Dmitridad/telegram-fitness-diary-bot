<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 05.04.2020
 * Time: 20:07
 */

namespace Classes;


class TrainingDays
{
    protected $db;
    protected $userId;
    protected $userMsg;
    protected $chatId;
    protected $bot;

    protected $days = [
        1 => 'пн',
        2 => 'вт',
        3 => 'ср',
        4 => 'чт',
        5 => 'пт',
        6 => 'сб',
        7 => 'вс',
    ];

    public function __construct($db, $userId, $userMsg, $chatId, $bot)
    {
        $this->init($db, $userId, $userMsg, $chatId, $bot);

        $result = $this->handleUserMsg($this->userMsg);
        if ($result == false) {

            return false;
        } else {
            $result = $this->createTrainingDays($result);
            if ($result != false) {
                ChatStatuses::updateChatStatus($this->db, $this->chatId, ChatStatuses::TRAINING_DAYS_CREATED);
                $this->sendSuccessMsg();
            }
        }
    }

    protected function init($_db, $_userId, $_userMsg, $_chatId, $_bot)
    {
        $this->db = $_db;
        $this->userId = $_userId;
        $this->userMsg = $_userMsg;
        $this->chatId = $_chatId;
        $this->bot = $_bot;
    }

    protected function handleUserMsg($userMsg)
    {
        $userMsg = str_replace(' ', '', $userMsg);
        $trainingDays = explode(',', $userMsg);
        $trainingDaysId = null;

        if (is_array($trainingDays)) {
            foreach ($trainingDays as $trainingDay) {
                if (!in_array($trainingDay, $this->days)) {

                    return false;
                }

                $trainingDaysId[] .= array_search($trainingDay, $this->days);
            }

            $trainingDays = $trainingDaysId;

            Logger::makeInfoLog($trainingDays);

            return $trainingDays;
        }
    }

    protected function createTrainingDays($trainingDays)
    {
        $currentDiaryId = $this->db->query("SELECT `current_diary` FROM `users` WHERE `tg_user_id` = ?i", $this->userId);
        $currentDiaryId = $currentDiaryId->fetch_assoc();
        $currentDiaryId = $currentDiaryId['current_diary'];

        try {
            foreach ($trainingDays as $trainingDay) {
                $record = ['user_id' => $this->userId, 'day_of_the_week' => $trainingDay, 'diary_id' => $currentDiaryId];
                $this->db->query('INSERT INTO `user_training_days` SET ?As', $record);

            }

            return true;
        } catch (\Exception $e) {
            Logger::makeErrorLog($e->getMessage());

            return false;
        }
    }

    protected function sendSuccessMsg()
    {
        try {
            $this->bot->sendMessage($this->chatId, 'Тренировочные дни успешно сохранены! Теперь ваш дневник готов! Можете перейти к его заполнению.', null, false, null);
            ChatStatuses::updateChatStatus($this->db, $this->chatId, ChatStatuses::SHOW_DIARY_MENU);

            new ChatMenu('diary_menu', $this->db, $this->userId, $this->userMsg, $this->chatId, $this->bot);
            //$this->showUserTrainingDays();
        } catch (\Exception $e) {
            Logger::makeErrorLog($e->getMessage());
        }
    }

    protected function showUserTrainingDays()
    {
        $result = $this->db->query("SELECT `name` 
                                    FROM `days_of_the_week`  
                                    INNER JOIN `user_training_days` ON `user_training_days`.`day_of_the_week` = `days_of_the_week`.`id`
                                    WHERE `user_training_days`.`user_id` = ?i and `user_training_days`.`diary_id` = (SELECT `current_diary` FROM `users` WHERE `tg_user_id` = ?i)", $this->userId, $this->userId);

        $daysArr = [];
        while (($data = $result->fetch_assoc()) !== null) {
            $daysArr[] .= $data['name'];
        }

        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array($daysArr), true, true); //создать класс или метод для создания клавиатуры, который будет возвращать уже готовую клаву
        $this->bot->sendMessage($this->chatId, 'Выберите тренировочный день для его заполнения', null, false, null, $keyboard);
    }
}