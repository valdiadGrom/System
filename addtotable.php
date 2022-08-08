<?php
session_start();
function tologin()
{
    header("Location: index.php");
    exit();
}
if (!empty($_SESSION['auth']) && ($_SESSION['role'] == 'admin')) {
    include_once "link.php";

    $errors = array();
    $success = array();
    if (isset($_POST['acceptbutton'])) {

        $datain = $_POST['datainput'];
        $textin = $_POST['textinput'];
        $summin = $_POST['summinput'];
        $typein = $_POST['selectType'];
        $subtypein = $_POST['selectSubtype'];
        $namein = $_POST['selectName'];
        $orgin = $_POST['selectOrg'];

        $querycheck = "SELECT * FROM `Cost` WHERE `Дата` = '$datain' AND `Наименование` = '$textin' AND `Сумма` = '$summin' AND `type`= '$typein' AND `Subtype` = '$subtypein' AND `name` = '$namein' AND `org` = '$orgin'";
        $resultcheck = mysqli_query($link, $querycheck);
        if (mysqli_num_rows($resultcheck) > 0) {
            $errors[] = "Отправленные данные уже присутвтвуют в базе";
        } else {
            $query = "INSERT INTO `Cost` (`Дата`, `Наименование`, `Сумма`, `type`, `Subtype`, `name`, `org`) VALUES ('$datain', '$textin', '$summin', '$typein', '$subtypein', '$namein', '$orgin')";
            if (mysqli_query($link, $query)) {
                $success[] = "Данные успешно добавлены";
            } else {
                $errors[] = "Данные не были добавлены по причине: " . mysqli_error($link);
            }
        }
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Добавление данных</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />
        <link href="css/nav.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/toast.min.css" rel="stylesheet">


    </head>

    <body>
        <?php



        include_once "sidenav.php";
        ?>
        <div class="main">
            <p>Подключение успешно установлено </p>
            <p>Добавление данных в таблицу вручную!</p>
            <p>Введите данные:</p>
            <?php
            if (isset($_POST['acceptbutton'])) {
                echo "<h1>$datain</h1>";
                echo "<h1>$textin</h1>";
                echo "<h1>$summin</h1>";
                echo "<h1>$typein</h1>";
                echo "<h1>$subtypein</h1>";
                echo "<h1>$namein</h1>";
                echo "<h1>$orgin</h1>";
            }
            ?>
            <form method='POST' id="send">
                <table class="tableCost">
                    <tr>
                        <th>Дата</th>
                        <th>Наименование</th>
                        <th>Сумма</th>
                        <th>Тип</th>
                        <th>Подтип</th>
                        <th>Имя</th>
                        <th>Организация</th>
                    </tr>
                    <tr>
                        <th><input type="date" name="datainput" form="send" required></th>
                        <th><input type="text" name="textinput" form="send" placeholder="Наименование" size="40" required></th>
                        <th><input type="number" name="summinput" form="send" placeholder="0" required></th>
                        <?php
                        $querytype = "SELECT * FROM `type`";
                        $resulttype = mysqli_query($link, $querytype);
                        echo "<th><select class ='selectType' name ='selectType' form='send' required>";
                        echo "<option value=''>Выберите</option>";
                        while ($object = mysqli_fetch_array($resulttype)) {
                            echo "<option value = '" . $object['type'] . "' > " . $object['type'] . " </option>";
                        }
                        echo "</select></th>";

                        $querytype1 = "SELECT `SubType` FROM `Subtype`";
                        $resulttype1 = mysqli_query($link, $querytype1);
                        echo "<th><select class = 'selectSubtype' name = 'selectSubtype' form='send' required>";
                        echo "<option value=''>Выберите</option>";
                        while ($object1 = mysqli_fetch_array($resulttype1)) {
                            echo "<option value = '" . $object1['SubType'] . "' > " . $object1['SubType'] . " </option>";
                        }
                        echo "</select></th>";

                        $querytypeName = "SELECT `name` FROM `name`";
                        $resulttypeName = mysqli_query($link, $querytypeName);
                        echo "<th><select class = 'selectName' name = 'selectName' form='send' required>";
                        echo "<option value=''>Выберите</option>";
                        while ($objectName = mysqli_fetch_array($resulttypeName)) {
                            echo "<option value = '" . $objectName['name'] . "' > " . $objectName['name'] . " </option>";
                        }
                        echo "</select></th>";

                        $querytypeOrg = "SELECT org FROM org";
                        $resulttypeOrg = mysqli_query($link, $querytypeOrg);
                        echo "<th><select class = 'selectOrg' name = 'selectOrg' form='send' required>";
                        echo "<option value=''>Выберите</option>";
                        while ($objectOrg = mysqli_fetch_array($resulttypeOrg)) {
                            echo "<option value = '" . $objectOrg['org'] . "' > " . $objectOrg['org'] . " </option>";
                        }
                        echo "</select></th>";

                        echo "<th><input type ='submit' name ='acceptbutton' form='send' value ='Добавить'></th>";
                        echo "</tr>";
                        echo "</form>";
                        ?>
        </div>
        <script src="js/jquery-3.6.0.min.js"></script>
        <script src="js/jquery.sumoselect.js"></script>
        <script src="js/menu.js"></script>
        <script src="js/toast.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.selectType').SumoSelect({
                    search: true,
                });
                $('.selectSubtype').SumoSelect({
                    search: true
                });
                $('.selectName').SumoSelect({
                    search: true
                });
                $('.selectOrg').SumoSelect({
                    search: true
                });

            })
        </script>
        <?php
        foreach ($errors as $error) {
            echo '<script> new Toast({ 
                title: "Ошибка",
                text: "' . $error . '",
                theme: "error",
                autohide: true,
                interval: 10000,
                });
            </script>';
        }
        foreach ($success as $succ) {
            echo '<script> new Toast({ 
                    title: "Успешно",
                    text: "' . $succ . '",
                    theme: "success",
                    autohide: true,
                    interval: 10000,
                    });
                </script>';
        }
        ?>
    </body>

    </html>
<?php
} else {
    tologin();
}
?>