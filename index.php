<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Персональные вишлисты</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать на сайт персональных вишлистов!</h1>
        <p>Здесь вы можете создать свой собственный вишлист и поделиться им с друзьями.</p>
        
        <h2>Как это работает?</h2>
        <ol>
            <li>Заполните форму ниже, чтобы создать вишлист.</li>
            <li>Сохраните ссылку на ваш вишлист.</li>
            <li>Делитесь ссылкой с друзьями, чтобы они могли посмотреть ваш список желаний.</li>
        </ol>

        <h2>Создать новый вишлист</h2>
        <form action="create.php" method="POST">
            <label for="name">Ваше имя:</label>
            <input type="text" id="name" name="name" required>

            <label for="wishlist_id">Идентификатор вишлиста (на английском):</label>
            <input type="text" id="wishlist_id" name="wishlist_id" required>

            <label for="password">Пароль для редактирования:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Создать вишлист</button>
        </form>

        <h2>Скриншоты</h2>
        <p>
            От лица владельца: <br>
            <img src="images/wishlist_owner.png" width="500" alt="">
        </p>
        <p>
            От лица гостя: <br>
            <img src="images/wishlist_guest.png" width="500" alt="">
        </p>
    </div>
</body>
</html>