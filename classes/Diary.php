<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 26.03.2020
 * Time: 14:03
 */

namespace Classes;

require_once('User.php');
require_once('ChatStatuses.php');
require_once ('Logger.php');


class Diary
{
    public static function createNewDiary($db, $userId, $name)
    {
        $diary = ['user_id' => $userId, 'name' => $name];

        try {
            $db->query('INSERT INTO `user_diaries` SET ?As', $diary);

            //получаем id только что созданного дневника
            $selectResponse = $db->query("SELECT * FROM `user_diaries` WHERE `user_id` = ?i ORDER BY `id` DESC LIMIT 1", $userId);
            $selectArr = $selectResponse->fetch_assoc();

            $newDiaryId = $selectArr['id'];

            //вставляем id только что созданного дневника в current_id
            $db->query('UPDATE `users` SET `current_diary` = ?d WHERE `tg_user_id` = ?i', $newDiaryId, $userId);
        } catch (\Exception $e) {
            Logger::makeErrorLog($e->getMessage());
            return false;
        }

        return true;
    }
}