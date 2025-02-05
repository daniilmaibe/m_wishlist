<?php
session_start();

// Проверяем, передан ли идентификатор вишлиста
if (!isset($_GET['id'])) {
    die("Идентификатор вишлиста не указан.");
}

$wishlist_id = basename($_GET['id']); // Безопасно извлекаем идентификатор
$wishlist_data_file = "wishlists/{$wishlist_id}_data.txt"; // Файл для хранения данных вишлиста

// Проверяем, существует ли такой вишлист
if (!file_exists($wishlist_data_file)) {
    die("Вишлист не найден.");
}

// Загружаем данные вишлиста
$wishlist_data = json_decode(file_get_contents($wishlist_data_file), true);

// Проверка пароля для входа в режим редактирования
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
    if (password_verify($_POST['password'], $wishlist_data['password_hash'])) {
        $_SESSION['is_owner'] = true; // Владелец подтвердил пароль
    } else {
        $error = "Неверный пароль!";
    }
}

// Выход из режима редактирования
if (isset($_GET['logout'])) {
    unset($_SESSION['is_owner']);
    header("Location: wishlist.php?id={$wishlist_id}");
    exit;
}

// Обработка добавления/удаления/изменения предметов
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['is_owner'])) {
    if (isset($_POST['add_item'])) {
        $new_item = [
            'name' => htmlspecialchars($_POST['item_name']),
            'description' => htmlspecialchars($_POST['item_description']),
            'is_gifted' => false
        ];
        $wishlist_data['items'][] = $new_item;
    } elseif (isset($_POST['delete_item'])) {
        $item_index = $_POST['item_index'];
        unset($wishlist_data['items'][$item_index]);
        $wishlist_data['items'] = array_values($wishlist_data['items']); // Переиндексация массива
    } elseif (isset($_POST['toggle_gifted'])) {
        $item_index = $_POST['item_index'];
        $wishlist_data['items'][$item_index]['is_gifted'] = !$wishlist_data['items'][$item_index]['is_gifted'];
    }

    // Сохраняем обновлённые данные
    file_put_contents($wishlist_data_file, json_encode($wishlist_data));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вишлист <?= $wishlist_data['name'] ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Вишлист <?= $wishlist_data['name'] ?></h1>

        <!-- Форма для ввода пароля (если владелец ещё не вошёл) -->
        <?php if (!isset($_SESSION['is_owner'])): ?>
            <div class="password-form">
                <form method="POST">
                    <label for="password">Введите пароль для редактирования:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit">Подтвердить</button>
                </form>
                <?php if (isset($error)): ?>
                    <p class="error"><?= $error ?></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="success">Вы вошли как владелец. <a href="?logout=1&id=<?= $wishlist_id ?>">Выйти</a></p>
        <?php endif; ?>

        <!-- Список предметов -->
        <div class="wishlist-items">
            <?php if (empty($wishlist_data['items'])): ?>
                <p>Пока что здесь пусто.</p>
            <?php else: ?>
                <?php foreach ($wishlist_data['items'] as $index => $item): ?>
                    <div class="item <?= $item['is_gifted'] ? 'gifted' : '' ?>">
                        <h3><?= $item['name'] ?></h3>
                        <p><?= $item['description'] ?></p>
                        <?php if (isset($_SESSION['is_owner'])): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="item_index" value="<?= $index ?>">
                                <button type="submit" name="toggle_gifted">
                                    <?= $item['is_gifted'] ? 'Отметить как неподаренное' : 'Отметить как подаренное' ?>
                                </button>
                                <button type="submit" name="delete_item">Удалить</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Форма для добавления нового предмета (только для владельца) -->
        <?php if (isset($_SESSION['is_owner'])): ?>
            <div class="add-item-form">
                <h2>Добавить новый предмет</h2>
                <form method="POST">
                    <label for="item_name">Название:</label>
                    <input type="text" id="item_name" name="item_name" required>

                    <label for="item_description">Описание:</label>
                    <textarea id="item_description" name="item_description" required></textarea>

                    <button type="submit" name="add_item">Добавить</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>