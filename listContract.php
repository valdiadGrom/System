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
        <title>Project</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/newProject.css" rel="stylesheet" />
        <link href="css/nav.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/style.css" rel="stylesheet" />

    </head>

    <body>
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <h1>Список договоров</h1>
            <!-- <div class="NewP"> -->
            <table class="tableCost">
                <thead>
                    <tr>
                        <th style="width: 5%;">Номер</th>
                        <th style="width: 5%;">Номер договора</th>
                        <th>Компания</th>
                        <th>Название проекта</th>
                        <th>Тэг</th>
                        <th style="width: 8%;">Менеджер</th>
                        <th style="width: 8%;">Инжинер</th>
                        <th style="width: 5%;">Дата начала</th>
                        <th style="width: 5%;">Дата поступления денег</th>
                        <th style="width: 5%;">Дата подписания акта</th>
                        <th style="width: 5%;">Дата окончания работ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $queryManager = "SELECT `Name` FROM `Managers`";
                    $resultManager = mysqli_query($link, $queryManager);
                    while ($rowM = mysqli_fetch_array($resultManager)) {
                        $arrayManagers[] = $rowM['Name'];
                    }

                    $query = "SELECT * FROM Subtype"; //WHERE SubType IN (SELECT DISTINCT Subtype FROM Cost)";
                    $result = mysqli_query($link, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";

                        if ($row['contract_number'] == null) {
                            echo "<td style='background-color: red;'><a href = newProject.php?Name=" . $row['SubType'] . ">Изменить</a></td>";
                        } else {
                            echo "<td>" . $row['contract_number'] . "</td>";
                        }

                        if ($row['counterparty'] == null) {
                            echo "<td style='background-color: red;'><a href = newProject.php?Name=" . $row['SubType'] . ">Изменить</a></td>";
                        } else {
                            echo "<td>" . $row['counterparty'] . "</td>";
                        }

                        echo "<td>" . $row['Name'] . "</td>";

                        echo "<td>" . $row['SubType'] . "</td>";

                        if ($row['Meneger'] == null || in_array($row['Meneger'], $arrayManagers) == false) {
                            echo "<td style='background-color: red;'><a href = newProject.php?Name=" . $row['SubType'] . ">Изменить</a></td>";
                        } else {
                            echo "<td>" . $row['Meneger'] . "</td>";
                        }

                        if ($row['Engineer'] == null) {
                            echo "<td style='background-color: red;'><a href = newProject.php?Name=" . $row['SubType'] . ">Изменить</a></td>";
                        } else {
                            echo "<td>" . $row['Engineer'] . "</td>";
                        }

                        if ($row['Start_Date'] == null) {
                            echo "<td style='background-color: red;'><a href = newProject.php?Name=" . $row['SubType'] . ">Изменить</a></td>";
                        } else {
                            echo "<td>" . $row['Start_Date'] . "</td>";
                        }

                        if ($row['Money_Date'] == null) {
                            echo "<td style='background-color: red;'><a href = newProject.php?Name=" . $row['SubType'] . ">Изменить</a></td>";
                        } else {
                            echo "<td>" . $row['Money_Date'] . "</td>";
                        }

                        if ($row['Act_Date'] == null) {
                            echo "<td style='background-color: red;'><a href = newProject.php?Name=" . $row['SubType'] . ">Изменить</a></td>";
                        } else {
                            echo "<td>" . $row['Act_Date'] . "</td>";
                        }

                        if ($row['End_Date'] == null) {
                            echo "<td style='background-color: red;'><a href = newProject.php?Name=" . $row['SubType'] . ">Изменить</a></td>";
                        } else {
                            echo "<td>" . $row['End_Date'] . "</td>";
                        }

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- </div> -->
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