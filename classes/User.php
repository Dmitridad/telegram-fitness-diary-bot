<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 15.03.2020
 * Time: 0:08
 */

namespace Classes;

ini_set('log_errors', 'On');
ini_set('error_log', './logs/php_errors.log');

require_once('Logger.php');

class User
{
    protected $db;
    protected $userId;
    protected $chatId;

    public function __construct($firstDataArr)
    {
        $this->initProperties($firstDataArr);
        $this->saveFirstUserData();
    }

    protected function initProperties($firstDataArr)
    {
        $this->db = $firstDataArr['db'];
        $this->chatId = $firstDataArr['chatId'];
        $this->userId = $firstDataArr['userId'];
    }

    protected function saveFirstUserData()
    {
        $result = $this->db->query("SELECT * FROM `users` WHERE `tg_user_id` = ?i AND `tg_chat_id` = ?i", $this->userId, $this->chatId);
        $resultArr = $result->fetch_assoc();

        if (empty($resultArr)){
            $user = array('tg_user_id' => $this->userId, 'tg_chat_id' => $this->chatId);
            $this->db->query('INSERT INTO `users` SET ?As', $user);
        } else Logger::makeInfoLog('Пользователь с такими начальными данными уже существует.');


    }

    protected function checkForNewUser()
    {
        //
    }

    public function makeRegistration()
    {

    }
}