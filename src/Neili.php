<?php

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


declare(strict_types=1);


namespace TelegramBot;

use Exception;
use Random\Engine\Secure;

/**
 * @method array sendMessage(int $chatId, string $text, string $keyboard = null, array $params = null)
 */
class Neili
{


    /**
     * debugging mode
     *
     * @constant int
     */
    const NONE = 0;
    const LOGGING = 1;
    const FATAL = 2;


    /**
     * logging type
     *
     * @constant int
     */
    const DEBUG = 10;
    const INFO = 11;
    const WARNINNG = 12;
    const ERROR = 13;


    /**
     * debugging type
     *
     * @var int
     */
    public $debugType = Neili::LOGGING;

    /**
     * bot api access token
     *
     * @var string
     */
    private $accessToken;


    /**
     * telegram api url
     *
     * @var string
     */
    public $apiUrl = 'https://api.telegram.org/bot';


    /**
     * api webhook hash
     *
     * @var string
     */
    private $webhookHash;


    /**
     * log file name
     *
     * @var string
     */
    public $logFile = 'neili.log';

    /**
     * setup neili object
     *
     * @param string $accessToken
     * @return Neili
     */

    public function __construct(string $accessToken)
    {
        if (function_exists('ini_set')) {
            ini_set('log_errors', '1');
            ini_set('error_log', $this->logFile);
        }
        $this->accessToken = $accessToken;
    }


    /**
     * writing log
     *
     * @param string $message
     * @param int $level
     * @return void
     */
    protected function log(string $message, int $level): void
    {

        if ($level == Neili::DEBUG) {
            $log = sprintf("[%s] [%s] %s\n", date('Y-m-d H:i:s'), 'DEBUG', $message);
        } elseif ($level == Neili::INFO) {
            $log = sprintf("[%s] [%s] %s\n", date('Y-m-d H:i:s'), 'INFO', $message);
        } elseif ($level == Neili::WARNINNG) {
            $log = sprintf("[%s] [%s] %s\n", date('Y-m-d H:i:s'), 'WARNNING', $message);
        } elseif ($level == Neili::ERROR) {
            $log = sprintf("[%s] [%s] %s\n", date('Y-m-d H:i:s'), 'ERROR', $message);
        } else {
            throw new Exception('invalid level type!');
        }

        file_put_contents($this->logFile, $log, FILE_APPEND);
    }

    /**
     * debugging bugs:{
     *
     * @param string $message
     * @param int $level
     * @return void
     */
    protected function debug(string $message, int $level): void
    {
        if ($this->debugType = Neili::LOGGING) {
            $this->log($message, $level);
        } elseif ($this->debugType == Neili::FATAL) {
            $this->log($message, $level);
            throw new Exception($message);
        } elseif ($this->debugType = Neili::NONE) {
            return;
        } else {
            throw new Exception('invalid debug type!');
        }
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
                $this->debug('The secret token was not found in the header', Neili::WARNINNG);
                return false;
            }
            if ($secretToken != $headers['X-Telegram-Bot-Api-Secret-Token']) {
                $this->debug('secret token invalid', Neili::WARNINNG);
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
    protected function request(string $method, array $params): array
    {
        $handler = curl_init();

        curl_setopt_array($handler, [
            CURLOPT_URL => $this->apiUrl . $this->accessToken . '/' . $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $params,
        ]);
        $result =  curl_exec($handler);

        if ($result === false) {
            $log = 'curl telegram api failed, error no: ' . curl_errno($handler);
            $this->debug($log, Neili::ERROR);
            return ['ok' => false, 'description' => $log];
        } else {
            $result = json_decode($result, true);
            if (!$result['ok']) {
                $this->debug('telegram method error ' . $result['description'], Neili::WARNINNG);
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
}
