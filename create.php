<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $wishlist_id = htmlspecialchars($_POST['wishlist_id']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хэшируем пароль

    // Проверяем, существует ли уже такой вишлист
    $wishlist_data_file = "wishlists/{$wishlist_id}_data.txt";
    if (file_exists($wishlist_data_file)) {
        echo "<h2>Вишлист с таким идентификатором уже существует! <a href='index.php'>Вернуться</a></h2> ";
    } else {
        // Создаем данные вишлиста
        $wishlist_data = [
            'name' => $name,
            'password_hash' => $password,
            'items' => []
        ];

        // Сохраняем данные в файл
        file_put_contents($wishlist_data_file, json_encode($wishlist_data));
        echo "<h2> Вишлист успешно создан! Перейдите по ссылке: <a href='wishlist.php?id={$wishlist_id}'>http://localhost/m_wishlist/wishlist.php?id={$wishlist_id}</a></h2>";
    }
}
?>