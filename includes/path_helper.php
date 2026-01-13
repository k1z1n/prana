<?php
/**
 * Функция для получения базового пути проекта
 * Работает корректно как в корне, так и в подкаталоге (например, public_html)
 * 
 * @return string Базовый путь проекта (например, '/' или '/public_html/')
 */
function getBasePath() {
    // Используем SCRIPT_NAME как основной источник информации
    if (!isset($_SERVER['SCRIPT_NAME'])) {
        return '/';
    }
    
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Получаем директорию из SCRIPT_NAME
    $dir = dirname($scriptName);
    
    // Нормализуем путь (заменяем обратные слеши на прямые)
    $dir = str_replace('\\', '/', $dir);
    
    // Если директория корневая или пустая, возвращаем '/'
    if ($dir === '/' || $dir === '.' || $dir === '\\' || empty($dir)) {
        return '/';
    }
    
    // Убеждаемся, что путь начинается и заканчивается слешем
    if (substr($dir, 0, 1) !== '/') {
        $dir = '/' . $dir;
    }
    if (substr($dir, -1) !== '/') {
        $dir .= '/';
    }
    
    return $dir;
}

/**
 * Функция для создания URL с учетом базового пути
 * 
 * @param string $path Относительный путь (например, '?page=login' или 'assets/css/style.css')
 * @return string Полный путь с учетом базового пути проекта
 */
function url($path = '') {
    try {
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
    } catch (Exception $e) {
        // В случае ошибки возвращаем путь как есть
        error_log('Ошибка в функции url(): ' . $e->getMessage());
        return $path;
    }
}
