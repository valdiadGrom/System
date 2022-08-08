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
        <title>Check</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/Fact.css" rel="stylesheet" />
        <link href="css/nav.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />

    </head>

    <body>
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <p>Insert into</p>
            <table>
                <tr>
                    <th><input type="text" name="name" id="Name"></th>
                    <th><input type="text" name="percent" id="percent"></th>
                </tr>
            </table>
        </div>
        <script src="js/jquery-3.6.0.min.js"></script>
        <script src="js/filter.js"></script>
        <script src="js/menu.js"></script>
    </body>

    </html>
<?php
} else {
    tologin();
}
?>