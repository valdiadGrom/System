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
        <title>Вывод из таблицы Name</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/nav.css" rel="stylesheet" />

    </head>

    <body>
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <h1>Таблица Name</h1>
            <table class="tableCost">
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Расшифровка</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT*FROM `name`";
                    $result = mysqli_query($link, $query);
                    while ($newrow = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $newrow['name'] . "</td>";
                        echo "<td>" . $newrow['title'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

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