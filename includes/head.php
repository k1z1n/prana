<?php
global $database;

require_once __DIR__ . '/path_helper.php';

session_start();
if(isset($_SESSION['user_id'])) {
    // (int) жесткое изменение типа данных
    $id   = (int) $_SESSION['user_id'];
    $stmt = $database->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $USER = $stmt->fetch(2);
}

if(isset($_SESSION['user_id']) && $USER['banned'] === 'banned'){
    // Удаляем все переменные сессии
    session_unset();
    // Уничтожаем сессию на сервере
    session_destroy();
    unset($_SESSION['user_id']);
    header('Location: ' . url('./'));
    exit;
}

if(isset($_GET['exit'])) {
    // Удаляем все переменные сессии
    session_unset();
    // Уничтожаем сессию на сервере
    session_destroy();
    unset($_SESSION['user_id']);
    header('Location: ' . url('./'));
    exit;
}

global $USER;