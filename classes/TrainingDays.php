<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 05.04.2020
 * Time: 20:07
 */

namespace Classes;

require_once('ChatStatuses.php');

class TrainingDays
{
    protected $db;
    protected $userMsg;
    protected $chatId;
    protected $userId;

    protected $days = [
        1 => 'пн',
        2 => 'вт',
        3 => 'ср',
        4 => 'чт',
        5 => 'пт',
        6 => 'сб',
        7 => 'вс',
    ];

    public function __construct($db, $userId, $userMsg)
    {
        $this->init($db, $userId, $userMsg);

        $result = $this->handleUserMsg($this->userMsg);
        if ($result == false) {

            return false;
        } else {
            $result = $this->createTrainingDays($result);
            if ($result != false) {
                $this->sendSuccessMsg();
            }
        }
    }

    protected function init($_db, $_userId, $_userMsg)
    {
        $this->db = $_db;
        $this->userId = $_userId;
        $this->userMsg = $_userMsg;
    }

    protected function handleUserMsg($userMsg)
    {
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
        } catch (\Exception $e) {
            Logger::makeErrorLog($e->getMessage());

            return false;
        }
    }

    protected function sendSuccessMsg()
    {
        $this->bot->sendMessage($this->chatId, 'Тренировочные дни успешно сохранены! Теперь ваш дневник готов! Можете перейти к его заполнению.', null, false, null);
        ChatStatuses::updateChatStatus($this->db, $this->chatId, ChatStatuses::SHOW_USER_TRAINING_DAYS);
        $this->showUserTrainingDays();
    }

    protected function showUserTrainingDays()
    {
        $result = $this->db->query("SELECT * FROM `user_training_days` WHERE `user_id` = ?i", $this->userId);
        $resultArr = $result->fetch_assoc();
    }
}