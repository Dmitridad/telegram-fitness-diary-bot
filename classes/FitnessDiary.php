<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 14.03.2020
 * Time: 18:54
 */

namespace Classes;

//require_once('./include/required_classes.php');

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
                ChatStatuses::updateChatStatus($this->db, $this->chatId, ChatStatuses::DIARY_CREATION);
                $this->createDiary();
                break;
            default:
                $this->handleUserInput();
                break;
        }
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

    protected function sendWelcomeMsg()
    {
        $this->welcomeMsg = "Алейкум асалам, $this->userFirstName, я бот из Люберец :)";
        $this->bot->sendMessage($this->chatId, $this->welcomeMsg, null, false, null); // отправляем приветственное сообщение
        ChatStatuses::updateChatStatus($this->db, $this->chatId, ChatStatuses::WELCOME);

        ChatKeyboard::sendCreateOrChooseDiaryKeyboard($this->chatId, $this->bot); // выводим начальное меню
        ChatStatuses::updateChatStatus($this->db, $this->chatId, ChatStatuses::SHOW_START_MENU);
    }

    protected function createDiary()
    {
        $this->bot->sendMessage($this->chatId, 'Введите название дневника', null, false, null);
        ChatStatuses::updateChatStatus($this->db, $this->chatId, ChatStatuses::ENTERING_DIARY_NAME);
    }

    protected function handleUserInput()
    {
        if ($this->compareStatuses(ChatStatuses::ENTERING_DIARY_NAME, ChatStatuses::DIARY_CREATION)) {
            $response = Diary::createNewDiary($this->db, $this->userId, $this->userMsg);

            if ($response == true) {
                ChatStatuses::updateChatStatus($this->db, $this->chatId, ChatStatuses::DIARY_CREATED);
                $this->bot->sendMessage($this->chatId,
                    'Поздравляю, твой новый дневник создан! Теперь введите дни по которым будете тренироваться и запишите их через запятую. Названия дней - пн, вт, ср, чт, пт, сб, вс.',
                    null, false, null);
                ChatStatuses::updateChatStatus($this->db, $this->chatId, ChatStatuses::ENTERING_TRAINING_DAYS);
            }
        } else if ($this->compareStatuses(ChatStatuses::ENTERING_TRAINING_DAYS, ChatStatuses::DIARY_CREATED)) {
            $trainingDays = new TrainingDays($this->db, $this->userId, $this->userMsg, $this->chatId, $this->bot);
        } else {
            $this->bot->sendMessage($this->chatId, 'Дефаулт', null, false, null);
        }
    }

    protected function compareStatuses($_currChatStatus, $_prevChatStatus)
    {
        $currChatStatus = ChatStatuses::selectCurrChatStatus($this->db, $this->chatId);
        $prevChatStatus = ChatStatuses::selectPrevChatStatus($this->db, $this->chatId);

        if ($currChatStatus == $_currChatStatus && $prevChatStatus == $_prevChatStatus) {

            return 1;
        }
    }
}