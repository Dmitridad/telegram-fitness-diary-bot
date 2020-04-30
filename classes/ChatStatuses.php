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
    /*const WELCOME = 1;
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
    const SHOW_USER_TRAINING_DIARIES = 15;*/

    const WELCOME = 1; // приветствие
    const SHOW_START_MENU = 2; // вывод начального меню
    const DIARY_CREATION = 3; // создание дневника
        const ENTERING_DIARY_NAME = 3.1; // ввод названия дневника
        const DIARY_CREATED = 3.2; // дневник создан
        const ENTERING_TRAINING_DAYS = 3.3; // ввод тренировочных дней
        const TRAINING_DAYS_CREATED = 3.4; // тренировочные дни созданы
    const DIARY_SELECTION = 4; // выбор дневника
        const SHOW_DIARY_SELECTION_MENU = 4.1; // вывод меню выбора дневника
        const DIARY_SELECTED = 4.2; // дневник выбран
    const SHOW_DIARY_MENU = 5; // вывод меню дневника
    const TRAINING_DAY_SELECTION = 6; // выбор тренировочного дня
        const TRAINING_DAY_SELECTED = 6.1; // тренировочный день выбран
    const TRAINING_DAY_ADDING = 7; // добавление тренировочного дня
        const SHOW_TRAINING_DAY_NAME_SELECTION_MENU = 7.1; // вывод меню выбора названия тренировочного дня
        const TRAINING_DAY_ADDED = 7.2; // тренировочный день добавлен
    const DIARY_NAME_CHANGING = 8; // изменение названия дневника
        const ENTERING_NEW_DIARY_NAME = 8.1; // ввод нового названия дневника
        const NEW_DIARY_NAME_SAVED = 8.2; // новое название дневника сохранено
    const DIARY_DELETING = 9; // удаление дневника
        const DIARY_DELETED = 9.1; // дневник удален
    const SHOW_TRAINING_DAY_MENU = 10; // вывод меню тренировочного дня
    const EXERCISE_ADDING = 11; // добавление упражнения
        const ENTERING_EXERCISE_NAME = 11.1; // ввод названия упражнения
        const ENTERING_EXERCISE_PARAMETERS = 11.2; // ввод параметров упражнения
        const EXERCISE_ADDED = 11.3; // упражнение добавлено
    const EXERCISE_CHANGING = 12; // изменение упражнения
        const SHOW_EXERCISE_SELECTION_MENU = 12.1; // вывод меню выбора упражнения
        const EXERCISE_SELECTED = 12.2; // упражнение выбрано
        const SHOW_EXERCISE_PARAMETERS_SELECTION_MENU = 12.3; // вывод меню выбора параметров упражнения
        const EXERCISE_PARAMETER_SELECTED = 12.4; // параметр упражнения выбран
        const ENTERING_NEW_VALUE_FOR_EXERCISE_PARAMETER = 12.5; // ввод нового значения параметра упражнения
        const NEW_VALUE_FOR_EXERCISE_PARAMETER_SAVED = 12.6; // новое значение параметра упражнения сохранено
    const EXERCISE_DELETING = 13; // удаление упражнения
        const EXERCISE_DELETED = 13.1; // упражнение удалено
    const TRAINING_DAY_NAME_CHANGING = 14; // изменение названия тренировочного дня
        const NEW_TRAINING_DAY_NAME_SAVED = 14.1; // новое название тренировочного дня сохранено
    const TRAINING_DAY_DELETING = 15; // удаление тренировочного дня
        const TRAINING_DAY_DELETED = 15.1; // тренировочный день удален


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