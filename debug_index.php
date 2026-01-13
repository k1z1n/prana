<?php
// Детальная отладка index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Отладка index.php</h1>";
echo "<pre>";

echo "=== Шаг 1: Подключение path_helper.php ===\n";
try {
    require_once __DIR__ . '/includes/path_helper.php';
    echo "✓ path_helper.php подключен успешно\n";
    echo "getBasePath(): " . getBasePath() . "\n";
} catch (Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "\n";
    exit;
}

echo "\n=== Шаг 2: Подключение database/connect.php ===\n";
try {
    require_once __DIR__ . '/database/connect.php';
    echo "✓ connect.php подключен успешно\n";
    if (isset($database)) {
        echo "✓ Переменная \$database создана\n";
    } else {
        echo "✗ Переменная \$database не создана\n";
    }
} catch (Exception $e) {
    echo "✗ Ошибка подключения к БД: " . $e->getMessage() . "\n";
    echo "Проверьте настройки в database/connect.php\n";
}

echo "\n=== Шаг 3: Подключение includes/head.php ===\n";
try {
    require_once __DIR__ . '/includes/head.php';
    echo "✓ head.php подключен успешно\n";
    if (isset($USER)) {
        echo "✓ Переменная \$USER создана\n";
        echo "  - user_id: " . ($USER['id'] ?? 'не определен') . "\n";
        echo "  - username: " . ($USER['username'] ?? 'не определен') . "\n";
    } else {
        echo "ℹ Переменная \$USER не создана (пользователь не авторизован)\n";
    }
} catch (Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "\n";
    echo "Стек вызовов:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Шаг 4: Проверка определения страницы ===\n";
$adminPages = ['add_product', 'add_category', 'add_gender', 'admin_products', 'admin_categories', 'admin_users', 'admin_genders', 'edit_gender', 'edit_product', 'edit_category', 'orders'];
$userPages = ['account', 'user_orders', 'setting_account', 'favorite', 'basket', 'order_success'];
$guestPages = ['register', 'login'];
$allStatusPages = ['main', 'catalog','product'];
$allPages = array_merge($adminPages, $userPages, $guestPages, $allStatusPages);

if (array_key_exists('page', $_GET)) {
    $raw = filter_input(INPUT_GET, 'page');
    if ($raw === null || $raw === '' || !in_array($raw, $allPages, true)) {
        echo "✗ Неверная страница: " . htmlspecialchars($raw ?? 'пусто') . "\n";
    } else {
        $page = $raw;
        echo "✓ Страница определена: " . $page . "\n";
    }
} else {
    $page = 'main';
    echo "✓ Страница по умолчанию: " . $page . "\n";
}

echo "\n=== Шаг 5: Проверка файла страницы ===\n";
if (isset($page)) {
    $pageFile = __DIR__ . '/pages/' . $page . '.php';
    if (file_exists($pageFile)) {
        echo "✓ Файл страницы существует: " . $pageFile . "\n";
    } else {
        echo "✗ Файл страницы не существует: " . $pageFile . "\n";
    }
}

echo "\n=== Шаг 6: Тест вывода HTML ===\n";
echo "✓ HTML вывод работает\n";

echo "</pre>";

// Попробуем вывести простую страницу
echo "<h2>Тест простой страницы</h2>";
echo "<p>Если вы видите этот текст, значит PHP работает.</p>";
echo "<p>Базовый путь: " . htmlspecialchars(getBasePath()) . "</p>";
echo "<p>URL для CSS: " . htmlspecialchars(url('assets/css/style.css')) . "</p>";
