<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 22.03.2020
 * Time: 2:33
 */

namespace Classes;

require_once ('./vendor/autoload.php');

class ChatKeyboard
{


    public static function sendCreateOrChooseDiaryKeyboard($chatId, $bot)
    {
        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Создать дневник тренировок", "Мой дневник тренировок")), true, true);
        $bot->sendMessage($chatId, 'Выбери подходящий вариант', null, false, null, $keyboard);
    }
}