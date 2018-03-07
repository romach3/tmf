<?php

require_once __DIR__.'/../vendor/autoload.php';
$env = new \Dotenv\Dotenv(__DIR__.'/../');
$env->load();

$MadelineProto = false;
/* Грузит ранее сохраненную сессию, ну или не грузит */
try {
    $MadelineProto = new \danog\MadelineProto\API('../sessions/mySession.madeline');
} catch (\danog\MadelineProto\Exception $e) {
    var_dump($e->getMessage());
}
/* Подключает, если сессии не было. Требует ввода кода, который пришлет телеграмм */
if ($MadelineProto === false) {
    $settings = ['app_info' => ['api_id' => getenv('API_ID'), 'api_hash' => getenv('API_HASH')]];
    $MadelineProto = new \danog\MadelineProto\API($settings);
    $MadelineProto->phone_login(getenv('PHONE'));
    $code = readline('Enter the code you received: ');
    $MadelineProto->complete_phone_login($code);
    echo 'Serializing MadelineProto to session.madeline...'.PHP_EOL;
    echo 'Wrote '.\danog\MadelineProto\Serialization::serialize('../sessions/mySession.madeline', $MadelineProto).' bytes'.PHP_EOL;
}
/* Загружает сообщения по id чата, названию канала и прочему */
var_dump(
    $MadelineProto->messages->getHistory([
        'peer' => '@laravel_pro',
        'offset_id' => 0,
        'offset_date' => 0,
        'add_offset' => 0,
        'limit' => 100,
        'max_id' => 0,
        'min_id' => 0,
        'hash' => 0,
    ])
);