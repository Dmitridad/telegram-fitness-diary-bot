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
        } catch (\Exception $e) {
            Logger::makeErrorLog($e->getMessage());
            return false;
        }

        return true;
    }
}