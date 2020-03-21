<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 14.03.2020
 * Time: 19:29
 */

namespace Classes;


class Logger
{
    public static function makeUpdateLog($update)
    {
        self::getDate('./logs/updateLog.log');
        file_put_contents('./logs/updateLog.log', print_r($update, 1), FILE_APPEND);
    }

    public static function makeInfoLog($data)
    {
        self::getDate('./logs/updateLog.log');
        file_put_contents('./logs/infoLog.log', print_r($data, 1), FILE_APPEND);
    }

    protected function getDate($filePath)
    {
        file_put_contents($filePath, '[' . date("Y-m-d H:i:s") . '] ', FILE_APPEND);
    }
}