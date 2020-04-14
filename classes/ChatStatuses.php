<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 21.03.2020
 * Time: 0:04
 */

namespace Classes;

require_once('Logger.php');


class ChatStatuses
{
    const WELCOME = 1;
    const CREATE_OR_SELECT_DIARY = 2;
    const ENTERING_DIARY_NAME = 3;
    const ENTERING_TRAINING_DAYS = 4;
    const SHOW_USER_TRAINING_DAYS = 5;
    const FILLING_OUT_TRAINING_DAYS = 6;
    const VIEWING_TRAINING_DAY = 7;
    const SHOW_TRAINING_DAY_INFO = 8;
    const ADDING_AN_EXERCISE = 9;
    const ENTERING_EXERCISE_NAME = 10;
    const ENTERING_EXERCISE_DESCRIPTION = 11;
    const ENTERING_EXERCISE_QUANTITATIVE_MEASURES = 12;
    const DELETING_AN_EXERCISE = 13;
    const SHOW_THIS_DAY_EXERCISES = 14;
    const SHOW_USER_TRAINING_DIARIES = 15;


    public static function updateChatStatus($db, $chatId, $status)
    {
        $currChatStatus = self::selectCurrChatStatus($db, $chatId);

        try {
            $db->query('UPDATE `users` SET `current_chat_status` = ?i, `previous_chat_status` = ?i WHERE `tg_chat_id` = ?i', $status, $currChatStatus, $chatId);
        } catch (\Exception $e) {
            Logger::makeErrorLog($e->getMessage());
        }
    }

    public static function selectCurrChatStatus($db, $chatId)
    {
        $result = $db->query("SELECT * FROM `users` WHERE `tg_chat_id` = ?i", $chatId);
        $resultArr = $result->fetch_assoc();
        $currChatStatus = $resultArr['current_chat_status'];

        return $currChatStatus;
    }

    public static function selectPrevChatStatus($db, $chatId)
    {
        $result = $db->query("SELECT * FROM `users` WHERE `tg_chat_id` = ?i", $chatId);
        $resultArr = $result->fetch_assoc();
        $prevChatStatus = $resultArr['previous_chat_status'];

        return $prevChatStatus;
    }
}