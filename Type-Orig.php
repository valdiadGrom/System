<?php
session_start();
function tologin()
{
    header("Location: index.php");
    exit();
}


if (!empty($_SESSION['auth']) && ($_SESSION['role'] == 'admin')) {

    include_once "link.php";
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Вывод из таблицы Type</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/nav.css" rel="stylesheet">
        <link href="css/okno.css" rel="stylesheet">
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">


    </head>

    <body>
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <h1>Таблица Type</h1>
            <table class="tableCost">
                <thead>
                    <tr>
                        <th>Тип</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT*FROM `type`";
                    $result = mysqli_query($link, $query);
                    while ($newrow = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $newrow['type'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div id="okno">
                <p>Всплывающее окошко для добавления</p>
                <a href="#" class="close">Закрыть всплывающее окно</a>
            </div>
            <a href="#okno">Вызвать всплывающее окно</a>
        </div>
    <?php
    mysqli_close($link);
} else {
    tologin();
}
    ?>
    </div>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/menu.js"></script>
    </body>

    </html>