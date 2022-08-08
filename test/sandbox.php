<?php
$host = '81.90.180.80:3316';
$user = 'profit';
$password = 'Profit2018';
$db_name = 'profiteng';
$link = mysqli_connect($host, $user, $password, $db_name);

if ($link === false) {
    die("Ошибка: " . mysqli_connect_error());
}
echo "<p>Подключение успешно установлено </p>";

$newQuery = "SELECT*FROM Cost";
$result = mysqli_query($link, $newQuery);
?>
<html>

<head>
    <title>Вывод из таблиц</title>
    <meta charset="utf-8" />
    <link href="../css/sumoselect.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/nav.css" rel="stylesheet" />

</head>

<body>
    <?php include_once "../sidenav.php"; ?>
    <input type="submit" value="Сбросить" onclick="reset()">
    <table id="Ftable">
        <thead>
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
                <th>
                    <?php
                    $querytype1 = "SELECT Дата FROM Cost";
                    $resulttype1 = mysqli_query($link, $querytype1);
                    echo "<select id='selectData' class = 'selectData' name = 'selectData' onchange='pip()'>";
                    echo "<option value='0'>Выбор</option>";
                    while ($object1 = mysqli_fetch_array($resulttype1)) {
                        echo "<option value = " . $object1['Дата'] . " > " . $object1['Дата'] . " </option>";
                    }
                    echo "</select>";
                    ?>
                </th>
                <th>
                    <?php
                    $querytype1 = "SELECT Наименование FROM Cost";
                    $resulttype1 = mysqli_query($link, $querytype1);
                    echo "<select id='selectnaim' class = 'selectnaim' name = 'selectnaim' onchange='pip()'>";
                    echo "<option value='0'>Выбор</option>";
                    while ($object1 = mysqli_fetch_array($resulttype1)) {
                        echo "<option value = " . $object1['Наименование'] . " > " . $object1['Наименование'] . " </option>";
                    }
                    echo "</select>";
                    ?>
                </th>
                <th>
                    <?php
                    $querytype1 = "SELECT Сумма FROM Cost";
                    $resulttype1 = mysqli_query($link, $querytype1);
                    echo "<select id='selectsumm' class = 'selectsumm' name = 'selectsumm' onchange='pip()'>";
                    echo "<option value='0'>Выбор</option>";
                    while ($object1 = mysqli_fetch_array($resulttype1)) {
                        echo "<option value = " . $object1['Сумма'] . " > " . $object1['Сумма'] . " </option>";
                    }
                    echo "</select>";
                    ?>
                </th>
                <th>
                    <?php
                    $querytype = "SELECT * FROM `type`";
                    $resulttype = mysqli_query($link, $querytype);
                    ?>
                    <select id="selectType" class="selectType" name="selectType" onchange='pip()'>
                        <option value='0'>Выбор</option>
                        <?php
                        while ($object = mysqli_fetch_array($resulttype)) {
                            echo "<option value = " . $object['type'] . " > " . $object['type'] . " </option>";
                        }
                        echo "</select>";
                        ?>
                </th>
                <th>
                    <?php
                    $querytype1 = "SELECT SubType FROM Subtype";
                    $resulttype1 = mysqli_query($link, $querytype1);
                    echo "<select id='selectSubtype' class = 'selectSubtype' name = 'selectSubtype' onchange='pip()'>";
                    echo "<option value='0'>Выбор</option>";
                    while ($object1 = mysqli_fetch_array($resulttype1)) {
                        echo "<option value = " . $object1['SubType'] . " > " . $object1['SubType'] . " </option>";
                    }
                    echo "</select>";
                    ?>
                </th>
                <th>
                    <?php
                    $querytypeName = "SELECT `name` FROM `name`";
                    $resulttypeName = mysqli_query($link, $querytypeName);
                    echo "<select id='selectName' class = 'selectName' name = 'selectName' onchange='pip()'>";
                    echo "<option value='0'>Выбор</option>";
                    while ($objectName = mysqli_fetch_array($resulttypeName)) {
                        echo "<option value = " . $objectName['name'] . " > " . $objectName['name'] . " </option>";
                    }
                    echo "</select>";
                    ?>
                </th>
                <th>
                    <?php
                    $querytypeOrg = "SELECT org FROM org";
                    $resulttypeOrg = mysqli_query($link, $querytypeOrg);
                    echo "<select id='selectOrg' class = 'selectOrg' name = 'selectOrg' onchange='pip()'>";
                    echo "<option value='0'>Выбор</option>";
                    while ($objectOrg = mysqli_fetch_array($resulttypeOrg)) {
                        echo "<option value = " . $objectOrg['org'] . " > " . $objectOrg['org'] . " </option>";
                    }
                    echo "</select>";
                    ?>

                </th>
            </tr>
        </thead>
        <?php
        while ($newrow = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $newrow['Дата'] . "</td>";
            echo "<td>" . $newrow['Наименование'] . "</td>";
            echo "<td>" . $newrow['Сумма'] . "</td>";
            echo "<td>" . $newrow['type'] . "</td>";
            echo "<td>" . $newrow['Subtype'] . "</td>";
            echo "<td>" . $newrow['name'] . "</td>";
            echo "<td>" . $newrow['org'] . "</td>";
            echo "</tr>";
        };
        echo "</table>";
        ?>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/jquery.sumoselect.js"></script>
        <script src="../js/menu.js"></script>
        <script src="../js/filter.js"></script>
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
                $('.selectData').SumoSelect({
                    search: true
                });
                $('.selectnaim').SumoSelect({
                    search: true
                });
                $('.selectsumm').SumoSelect({
                    search: true
                });
            })
        </script>
</body>

</html>