<?php

declare(strict_types=1);


/**
 * lightweight library for managing and creating automation for telegram api bots.
 *
 * @version 1.0.0
 * @author Mr Afaz
 * @package neili
 * @copyright Copyright 2023 Neili library
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/imafaz/neili
 */

namespace TelegramBot;

use EasyLog\Logger;


/**
 * @method void __construct(string $accessToken)
 * @method void __set($property, $value)
 * @method void __call($method, $arguments)
 * @method array|false handleUpdate(string $secretToken = null)
 * @method array sendMessage(int $chatId, string $text, string $keyboard = null, array $params = null)
 * @method string keyboard(string $secretToken = null)
 * @method array|false handleUpdate(string $secretToken = null)
 */
class Neili
{

    /**
     * bot api access token
     *
     * @var string
     */
    private $accessToken;


    /**
     * loggger object
     *
     * @var object
     */
    private $logger;


    /**
     * telegram api url
     *
     * @var string
     */
    public $apiUrl = 'https://api.telegram.org/bot';



    /**
     * log file name
     *
     * @var string
     */
    private $logFile = 'neili.log';


    /**
     * print logs
     *
     * @var string
     */
    private $printlog = false;

    /**
     * setup neili object
     *
     * @param string $accessToken
     * @return Neili
     */

    public function __construct(string $accessToken)
    {
        $this->logger = new Logger($this->logFile, $this->printlog);
        $this->accessToken = $accessToken;
    }



    /**
     * setup Neili property
     *
     * @param string $property
     * @param string $value
     * @return void
     */
    public function __set($property, $value)
    {
        if ($property == 'printLog') {
            $this->logger->printLog = $value;
        } elseif ($property == 'logFile') {
            $this->logger->logFile = $value;
            if (function_exists('ini_set')) {
                ini_set('log_errors', '1');
                ini_set('error_log', $this->logFile);
            }
        }
    }


    /**
     * using custom method
     *
     * @param method $property
     * @param string $value
     * @return void
     */
    public function __call($method, $arguments)
    {
        return $this->request($method, $arguments[0]);
    }





    /**
     * receive telegram hook updates
     *
     * @param string $hash
     * @return array|bool
     */
    public function handleUpdate(string $secretToken = null)
    {
        $headers = getallheaders();
        if (!is_null($secretToken)) {
            if (!isset($headers['X-Telegram-Bot-Api-Secret-Token'])) {
                $this->logger->error('The secret token was not found in the header');
                return false;
            }
            if ($secretToken != $headers['X-Telegram-Bot-Api-Secret-Token']) {
                $this->logger->error('secret token invalid');
                return false;
            }
        }
        $hook = json_decode(file_get_contents('php://input'), true);
        return $hook;
    }



    /**
     * send http request to telegram api
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    private function request(string $method, array $params): array
    {
        $handler = curl_init();

        curl_setopt_array($handler, [
            CURLOPT_URL => $this->apiUrl . $this->accessToken . '/' . $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_SSL_VERIFYPEER =>false
        ]);
        $result =  curl_exec($handler);

        if ($result === false) {
            $log = 'curl telegram api failed, error no: ' . curl_errno($handler);
            $this->logger->error($log);
            return ['ok' => false, 'description' => $log];
        } else {
            $result = json_decode($result, true);
            if (!$result['ok']) {
                $this->logger->debug('telegram method error ' . $result['description']);
            }
            return $result;
        }
    }


    /**
     * send http request to telegram api
     *
     * @param int $chatId
     * @param string $text
     * @param string $keyboard
     * @param array|null $params
     * @return array
     */
    public function sendMessage(int $chatId, string $text, string $keyboard = null, array $params = null): array
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => $keyboard,
        ];
        return $this->request('sendMessage', is_null($params) ? $data : array_merge($data, $params));
    }


    /**
     * create keyboard
     *
     * @param array $buttons
     * @param int $raw
     * @param bool $resize
     * @return json
     */
    public static function keyboard(array $buttons, int $raw = 2, bool $resize = true)
    {
        $buttonChunks = array_chunk($buttons, $raw);
        $keyboard = array_map(fn ($buttonRow) => array_map(fn ($button) => ['text' => $button], $buttonRow), $buttonChunks);
        return json_encode(['resize_keyboard' => $resize, 'keyboard' => $keyboard]);
    }

    /**
     * create inlineKeyboard
     *
     * @param array $buttons
     * @param int $raw
     * @return json
     */
    public static function inlineKeyboard(array $keyboard, int $row = 2)
    {
        $buttonChunks = array_chunk($keyboard, $row, true);
        $inlineKeyboard = [];
        foreach ($buttonChunks as $buttonRow) {
            $buttons = [];
            foreach ($buttonRow as $text => $callbackData) {
                $buttons[] = ['text' => $text, 'callback_data' => $callbackData];
            }
            $inlineKeyboard[] = $buttons;
        }
        return json_encode(['inline_keyboard' => $inlineKeyboard]);
    }
}
