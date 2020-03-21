<?php



const TOKEN = '1108314590:AAFH6Mv9UsuCCK_6tesW_nZTYzWNJ29PCvQ';



$url = 'https://api.telegram.org/bot'.TOKEN. '/setWebhook';

$proxy = '34.84.57.254:22080';

$params = [

    'url' => 'https://telegram-bot-first.000webhostapp.com/index.php'

];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_HEADER, false);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

$response = curl_exec($ch);
$updates = json_decode($response, JSON_OBJECT_AS_ARRAY);
print_r($updates);