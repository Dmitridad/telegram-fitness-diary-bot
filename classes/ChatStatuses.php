<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 21.03.2020
 * Time: 0:04
 */

namespace Classes;


class ChatStatuses
{
    const WELCOME = 1;
    const CREATE_OR_SELECT_DIARY = 2;
    const ENTERING_DIARY_NAME = 3;
    const SELECTION_OF_TRAINING_DAYS = 4;
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
}