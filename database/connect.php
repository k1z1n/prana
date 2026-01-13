<?php

$host   = 'localhost';
$db     = 'k1z1nksb_prana';
$charset= 'utf8';
$user   = 'k1z1nksb_prana';
$pass   = 'k1z1nksb_prana1';
$dsn    = "mysql:host={$host};dbname={$db};charset={$charset}";

// Опции PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // выбрасывать исключения
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // ассоциативный массив по умолчанию
    PDO::ATTR_EMULATE_PREPARES   => false,                  // отключить эмуляцию подготовленных запросов
];

try {
    $database = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log('[' . date('Y-m-d H:i:s') . '] Произошла какая-то ошибка: ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../logs/database.log');
    // На продакшене не показываем детали ошибки, только логируем
    if (ini_get('display_errors')) {
        die('Ошибка подключения к базе данных. Проверьте логи или обратитесь к администратору.');
    } else {
        die('Ошибка подключения к базе данных.');
    }
}