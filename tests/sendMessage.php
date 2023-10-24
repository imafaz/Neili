<?php

include('../src/Neili.php');


use TelegramBot\Neili;

$token = '6442038046:AAGfKm3q-YaNF38ps1EwMUN0QOeKbg7DX4M';
$bot = new Neili($token);


$chatId = '1826312667'; // telegram user id
$message = 'hello  this message sended with neili!';
$bot->sendMessage($chatId, $message);
