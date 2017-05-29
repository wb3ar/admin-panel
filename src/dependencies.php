<?php

// Установка внутренней кодировки в UTF-8
mb_internal_encoding('utf-8');

// Автозагрузчик классов
spl_autoload_register(function ($classname) {
    require __DIR__.'/../src/classes/'.$classname.'.php';
});

$settings = require __DIR__.'/../src/settings.php';

// Запуск сессии, либо возобновление
session_start();
