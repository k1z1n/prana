<?php
/**
 * Функция для получения базового пути проекта
 * Работает корректно как в корне, так и в подкаталоге (например, public_html)
 * 
 * @return string Базовый путь проекта (например, '/' или '/public_html/')
 */
function getBasePath() {
    // Получаем путь к index.php относительно корня документа
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Если index.php находится в корне, возвращаем '/'
    if (dirname($scriptName) === '/' || dirname($scriptName) === '\\') {
        return '/';
    }
    
    // Иначе возвращаем путь к директории с добавлением слеша в конце
    $basePath = dirname($scriptName);
    // Убеждаемся, что путь начинается со слеша и заканчивается слешем
    if (substr($basePath, 0, 1) !== '/') {
        $basePath = '/' . $basePath;
    }
    if (substr($basePath, -1) !== '/') {
        $basePath .= '/';
    }
    
    return $basePath;
}

/**
 * Функция для создания URL с учетом базового пути
 * 
 * @param string $path Относительный путь (например, '?page=login' или 'assets/css/style.css')
 * @return string Полный путь с учетом базового пути проекта
 */
function url($path = '') {
    $basePath = getBasePath();
    
    // Если путь пустой, возвращаем базовый путь
    if (empty($path)) {
        return $basePath;
    }
    
    // Если путь начинается с '?', просто добавляем базовый путь
    if (substr($path, 0, 1) === '?') {
        return $basePath . $path;
    }
    
    // Если путь начинается с './', убираем './' и добавляем базовый путь
    if (substr($path, 0, 2) === './') {
        return $basePath . substr($path, 2);
    }
    
    // Если путь начинается с '../', убираем '../' и добавляем базовый путь
    if (substr($path, 0, 3) === '../') {
        return $basePath . substr($path, 3);
    }
    
    // Если путь начинается с '/', это абсолютный путь - возвращаем как есть
    if (substr($path, 0, 1) === '/') {
        return $path;
    }
    
    // Для всех остальных случаев добавляем базовый путь
    return $basePath . ltrim($path, '/');
}
