<?php
// Временный файл для отладки на Beget
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Отладка путей на Beget</h1>";
echo "<pre>";

echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'не определен') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'не определен') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'не определен') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'не определен') . "\n";
echo "__DIR__: " . __DIR__ . "\n";
echo "__FILE__: " . __FILE__ . "\n";

echo "\n--- Тест функции getBasePath() ---\n";
require_once __DIR__ . '/includes/path_helper.php';
$basePath = getBasePath();
echo "getBasePath() вернул: '" . $basePath . "'\n";

echo "\n--- Тест функции url() ---\n";
echo "url('assets/css/style.css'): " . url('assets/css/style.css') . "\n";
echo "url('?page=login'): " . url('?page=login') . "\n";
echo "url('./'): " . url('./') . "\n";

echo "\n--- Проверка подключения файлов ---\n";
$connectFile = __DIR__ . '/database/connect.php';
if (file_exists($connectFile)) {
    echo "Файл connect.php существует: ДА\n";
} else {
    echo "Файл connect.php существует: НЕТ\n";
}

$headFile = __DIR__ . '/includes/head.php';
if (file_exists($headFile)) {
    echo "Файл head.php существует: ДА\n";
} else {
    echo "Файл head.php существует: НЕТ\n";
}

echo "</pre>";
