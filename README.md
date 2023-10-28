# lightweight php library for telegram manage bot

neili is a lightweight library for managing and creating automation for telegram api bots.

---

- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Usage](#usage)
- [Available Method](#available-method)
- [License](#license)

---

## Requirements

This library is supported by **PHP versions 7.0** or higher <br>
neili uses the **EasyLog version ^1**



## Installation

The preferred way to install this extension is through [Composer](http://getcomposer.org/download/).

To install **Neili**, simply:

    $ composer require imafaz/Neili


## Quick Start

To use this library with **Composer**:

```php
require __DIR__ . '/vendor/autoload.php';

use TelegramBot\Neili;
```

## Usage

Create an instance of Neili
```php
$bot = new Neili('token');
```

Send a simple message:<br>
```php
$chatId = '1826312667'; 
$message = 'hello  this message sended with neili!';
$bot->sendMessage($chatId, $message);
```

## Available Method

### - __construct

```php
// $bot = new Neili(string $accessToken);

// example:

$accessToken = 'xxxxx'; 
$bot = new Neili($accessToken);
```

| Atttribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $accessToken | telegram bot access token | string | yes | null |

**# Return** (void)


If you don't find the method you need, you can use below:
- methods: [TELEGRAM DOCUMENT](https://core.telegram.org/bots/api)
- example: tests/customMethod.php

### - custom method

```php
// $bot->methodName(array $parameters);

//example:

$chatId = '1826312667';
$bot->sendPhoto([
    'chat_id' => $chatId,
    'photo' => new CURLFile('test.jpg')
]);

```

| Atttribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $parameters | telegram required params | array | yes | null |

**# Return** (array)

### - handleUpdate

```php
// $bot->handleUpdate(string $secretToken = null);

// example:

// secure
$secret = 'xxxxx';
$bot->handleUpdate($secret);
// not secure
$bot->handleUpdate();
```

| Atttribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $secret | wenhook secret token | string | no | null |

**# Return** (array)

### - sendMessage

```php
// $bot->sendMessage($chatId, $message,$keyboard,$params);

// example:

$chatId = '1826312667'; 
$message = 'hello  this message sended with neili!';

// basic
$bot->sendMessage($chatId, $message);
// advance
$bot->sendMessage($chatId, $message,null,['parse_mode'=>'html','disable_web_page_preview'=>true]);
```

| Atttribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $chatId | telegram user/group/channel uniq id | int | yes | null |
| $message | you need message | string | yes | null |
| $keyboard | json keyboard | string | no | null |
| $params | custom parameters | array | no | null |

**# Return** (array)

### - keyboard

```php
// Neili::keyboard(array $buttons, int $raw = 2, bool $resize = true);

// example:

$buttons = ['button1','button2','button3','button4','button5'];
Neili::keyboard($buttons);
```

| Atttribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $buttons | you need buttons | array | yes | null |
| $raw | button count per raw | int | yes | 2 |
| $resize | resize keyboard | bool | yes | false |

**# Return** (json)

### - inlineKeyboard

```php
// Neili::inlineKeyboard(array $buttons, int $raw = 2);

// example:

$buttons = ['button1'=>'customdata','button2'=>'customdata','button3'=>'customdata'];
Neili::inlineKeyboard($buttons);
```

| Atttribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $buttons | you need buttons | array | yes | null |
| $raw | button count per raw | int | yes | 2 |

**# Return** (json)


# License
- This script is licensed under the [MIT License](https://opensource.org/licenses/MIT).