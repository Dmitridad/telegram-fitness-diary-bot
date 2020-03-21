<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 14.03.2020
 * Time: 18:54
 */

namespace Classes;

require_once('./vendor/autoload.php');
require_once('User.php');
require_once('Logger.php');

use Krugozor\Database\Mysql\Mysql as Mysql;

class FitnessDiary
{
    protected $updateData;
    protected $db;
    protected $apiConfig;
    protected $bot;
    protected $welcomeMsg;
    protected $userId;
    protected $chatId;
    protected $keyboard;
    protected $userFirstName;
    protected $authMsg;
    protected $user;
    protected $userMsg;

    public function __construct($_updateData = null)
    {
        $this->initProperties($_updateData);

        switch ($this->userMsg) {
            case '/start':
                $this->saveFirstUserData();
                $this->sendWelcomeMsg();
                break;
            case 'Создать дневник тренировок':
                $this->createDiary();
                break;
            default:
                $this->bot->sendMessage($this->chatId, 'Дефаулт', null, false, null, $this->keyboard);
        }
    }

    protected function initProperties($_updateData = null)
    {
        $this->updateData = $_updateData;
        $this->db = Mysql::create('localhost', 'id12898702_dmitridad', 'dmitridad123456')
            ->setCharset('utf8')
            ->setDatabaseName('id12898702_fitness_diary_bot');
        $this->apiConfig = include_once('./config/config.php');
        $this->bot = new \TelegramBot\Api\BotApi($this->apiConfig['token']);
        $this->userId = $this->updateData['message']['from']['id'];
        $this->chatId = $this->updateData['message']['chat']['id'];
        $this->userFirstName = $this->updateData['message']['from']['first_name'];
        $this->userMsg = $this->updateData['message']['text'];
    }

    protected function sendWelcomeMsg()
    {
        $this->welcomeMsg = "Алейкум асалам, $this->userFirstName, я бот из Люберец :)";
        $this->bot->sendMessage($this->chatId, $this->welcomeMsg, null, false, null, $this->keyboard); // отправляем приветственное сообщение
        $this->keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Создать дневник тренировок", "Мой дневник тренировок")), true, true);
        $this->bot->sendMessage($this->chatId, 'Выбери подходящий вариант', null, false, null, $this->keyboard);
        //$this->sendAuthMsg();
    }

    protected function saveFirstUserData()
    {
        $firstDataArr = array('db' => $this->db, 'userId' => $this->userId, 'chatId' => $this->chatId);
        $this->user = new User($firstDataArr);
    }

    protected function createDiary()
    {
        
    }

//    protected function sendAuthMsg()
//    {
//        $this->authMsg = "Перед тем как ебашить пройди аутентификацию :)";
//        $this->keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Войти", "Зарегистрироваться")), true, true);
//        $this->bot->sendMessage($this->chatId, $this->authMsg, null, false, null, $this->keyboard);
//    }
//
//
//    protected function sendRegistrationMsg()
//    {
//        $registrationMsg = "Напишите ваш email и пароль в формате 'Регистрация:email||пароль'";
//        $this->bot->sendMessage($this->chatId, $registrationMsg, null, false, null, $this->keyboard);
//        $this->userRegistration();
//
//    }
//
//    protected function userRegistration($matches)
//    {
//        Logger::makeInfoLog($matches);
//    }
//
//    protected function makeAuth()
//    {
//        //
//    }
}