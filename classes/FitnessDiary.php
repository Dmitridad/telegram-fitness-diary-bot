<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 14.03.2020
 * Time: 18:54
 */

namespace Classes;

require_once ('./vendor/autoload.php');
require_once ('User.php');
require_once ('Logger.php');
require_once ('ChatStatuses.php');
require_once ('ChatKeyboard.php');

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
        $this->requestHandler();
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

    protected function requestHandler()
    {
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

    protected function sendWelcomeMsg()
    {
        $this->welcomeMsg = "Алейкум асалам, $this->userFirstName, я бот из Люберец :)";
        $this->bot->sendMessage($this->chatId, $this->welcomeMsg, null, false, null, $this->keyboard); // отправляем приветственное сообщение

        ChatKeyboard::sendCreateOrChooseDiaryKeyboard($this->chatId, $this->bot);
    }



    protected function saveFirstUserData()
    {
        $firstDataArr = [
            'db' => $this->db,
            'userId' => $this->userId,
            'chatId' => $this->chatId
        ];

        $this->user = new User($firstDataArr);
    }

    protected function createDiary()
    {
//        $firstDataArr = [
//            'db' => $this->db,
//            'userId' => $this->userId,
//            'chatId' => $this->chatId
//        ];
//
//        $this->user = new User($firstDataArr);
//
//        $this->user->updateChatStatus(ChatStatuses::ENTERING_DIARY_NAME);
    }
}