<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 01.05.2020
 * Time: 13:46
 */

namespace Classes;


class ChatMenu
{
    protected $db;
    protected $userId;
    protected $userMsg;
    protected $chatId;
    protected $bot;

    public function __construct($menuName, $db, $userId, $userMsg, $chatId, $bot)
    {
        $this->init($db, $userId, $userMsg, $chatId, $bot);

        switch ($menuName) {
            case 'diary_menu':
                $this->showDiaryMenu();
                break;
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

    protected function showDiaryMenu()
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