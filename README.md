# Neili library
### neili is a lightweight library for managing and creating automation for telegram api bots.

## Installation
- You can install neili using composer or by cloning this repository:
- Using Composer: `composer require imafaz/neili`
- Using git: `git clone https://github.com/imafaz/neili.git`

## Usage
- Using Composer
- If you're using composer autoload, you first need to include it in your PHP script. For example, if your vendor directory is in the root of your project, you would use: <br>
`require_once __DIR__ . '/vendor/autoload.php';`
- Using Git
- If you have cloned this repository from git, you can include the Logger class like this: <br>
`require_once '/path/to/neili/src/Neili.php';`<br>

- Then import the Neili class into your PHP script: <br>
`use TelegramBot\Neili;`<br>
- Create an instance of Neili: <br>
`$bot = new Neili('token');`<br
- Send a simple message:<br>
```php
$chatId = '1826312667'; // telegram user id
$message = 'hello  this message sended with neili!';
$bot->sendMessage($chatId, $message);
```

### Usable methods:
- `sendMessage(int $chatId, string $text, string $keyboard = null, array $params = null)` <br>

### If you don't find the method you need, you can use below:
- `$bot->methodName(array $parameters)` <br>
- More methods: [TELEGRAM DOCUMENT](https://core.telegram.org/bots/api)

# License
- Medoo is released under the [MIT License](https://opensource.org/licenses/MIT).