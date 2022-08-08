<?php
session_start();
function tologin()
{
    header("Location: index.php");
    exit();
}
if (!empty($_SESSION['auth'])) {
    include_once "link.php";
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Список пользователей</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <link href="css/style.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/nav.css" rel="stylesheet" />
    </head>

    <body>
    <?php
    include_once "sidenav.php";

    echo "<div class = 'main'>";

    echo "<h1>Список пользователей</h1>";

    $query = "SELECT * FROM `people`";
    $result = mysqli_query($link, $query);
    echo "<table class = 'tableCost' style='width: 100%;'>";
    echo "<tr>";
    echo "<th>Фио пользователя</th>";
    echo "<th>Логин пользователя</th>";
    echo "<th>Роли пользователя</th>";
    echo "<th>Изменить пользователя</th>";
    echo "<th>Удалить пользователя</th></tr>";

    while ($data = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<th>" . $data['name'] . "</th>";
        echo "<th>" . $data['user'] . "</th>";
        echo "<th>" . $data['role'] . "</th>";
        echo "<th><a href=#>в работе</a></th>";
        echo "<th><a href=#>в работе</a></th>";
        echo "</tr>";
    };
    echo "</table>";
    echo "</div>";
} else {
    tologin();
}
    ?>
    <script src="js/menu.js"></script>
    </body>

    </html>