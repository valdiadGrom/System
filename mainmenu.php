<?php
session_start();
function tologin()
{
    header("Location: index.php");
    exit();
}
if (!empty($_SESSION['auth'])) {
    include_once "link.php";
    $query = "SELECT user_role FROM users WHERE user_id = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($link, $query);
    $data = mysqli_fetch_array($result);
    $_SESSION['role'] = $data['user_role'];
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Главное меню</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <link href="css/style.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/nav.css" rel="stylesheet" />

    </head>

    <body>
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <?php

            echo "<h2>Добро пожаловать в главное меню</h2>";



            switch ($_SESSION['role']) {
                case 'admin':
                    echo "<p>Поздравляю, вы админ</p>";
                    echo "<h1>Наполнение главного меню!!!</h1>";
                    echo "<p>Планы:</p>";
                    echo "<p>1. Разделение меню для пользователей</p>";
                    echo "<p>2. Добавить несколько таблиц и доступы к ним</p>";
                    echo "<p>3. Изменить IP в файлах как будет релиз</p>";
                    break;

                case 'user':
                    echo "Добро пожаловать пользователь";
                    echo "Пока здесь ничего нет, но в будущем мы добавим";
                    break;
                case 'manager':
                    echo "<p>Добро пожаловать менеджер: </p>";
                    echo "<p>Вам доступны таблицы:</p>";
                    echo "<p>1. Страница создания проекта</p>";
                    echo "<p>2. Страница списка всех проектов Check</p>";
                    echo "<p>На эти страницы можно перейти через боковое меню</p>";
                    break;
                case 'engineer':
                    echo "<p>Добро пожаловать инжинер: </p>";
                    echo "<p>Вам доступны таблицы:</p>";
                    echo "<p>1. Страница списка всех проектов Check</p>";
                    echo "<p>2. Страница расчета рентабельности проекта</p>";
                    echo "<p>На эти страницы можно перейти через боковое меню</p>";
                    break;
                case 'buyer':
                    echo "<p>Добро пожаловать менеджер по закупкам: </p>";
                    echo "<p>Вам доступны таблицы:</p>";
                    echo "<p>1. Страница списка всех проектов Check</p>";
                    echo "<p>2. Страница расчета рентабельности проекта</p>";
                    echo "<p>На эти страницы можно перейти через боковое меню</p>";
                    break;
                case 'document_specialist':
                    echo "<p>Добро пожаловать документовед: </p>";
                    echo "<p>Вам доступны таблицы:</p>";
                    echo "<p>1. Страница списка всех проектов Check</p>";
                    echo "<p>2. Страница списка всех договоров</p>";
                    echo "<p>На эти страницы можно перейти через боковое меню</p>";
                    break;
                default:
                    tologin();
            }

            mysqli_close($link);
            ?>
        </div>
        <script src="js/menu.js"></script>

    </body>

    </html>
<?php
} else {
    tologin();
}
?>