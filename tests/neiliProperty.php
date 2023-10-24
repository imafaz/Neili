<?php

// include autoload or Neili.php
include('../src/Neili.php');


// use Neili class
use TelegramBot\Neili;


// create Neili object
$bot = new Neili('6442038046:AAGfKm3q-YaNF38ps1EwMUN0QOeKbg7DX4M');


// set custom proxy server or load balancer
// default: https://api.telegram.org/bot
$bot->apiUrl = 'https://api.telegram.org/bot';


// set custom log file name
// default: neili.log
$bot->logFile = 'neili.log';


// valid value: Neili::FATAL , Neili::LOGGING , Neili::NONE
// default , recommended: Neili::LOGGING
$bot->debugType = Neili::LOGGING;


// send message
$chatId = '1826312667';
$message = 'This message was sent by Neili Library';
$bot->sendMessage($chatId, $message);
