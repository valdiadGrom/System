<?php
session_start();
function tologin()
{
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['auth'] == true && $_SESSION['role'] == 'admin') {

    include_once "../link.php";
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Managers</title>
        <meta charset="utf-8" />
        <!-- <link href="css/sumoselect.css" rel="stylesheet" /> -->
        <link href="../css/nav.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="../css/style.css" rel="stylesheet" />
        <!-- <link href="css/calc.css" rel="stylesheet" />
        <link href="css/newProject.css" rel="stylesheet" />
        <link href="css/toast.min.css" rel="stylesheet"> -->
    </head>

    <body onload="rating()">
        <?php include_once "../sidenav.php"; ?>
        <div class="main">
            <h1>Премии менеджеров</h1>
            <table class="tableCost" style="width: 100%;">
                <thead>
                    <tr>
                        <th rowspan="2">Менеджер</th>
                        <th colspan="10">Последние 10 месяцев</th>
                        <th rowspan="2">Итого</th>
                        <th rowspan="2">Ранг</th>
                    </tr>
                    <tr>
                        <?php
                        $dataS = date("Y-m");
                        $months[] = date("Y-m");
                        $months_reverse[] = date("F-Y");

                        for ($i = 1; $i < 10; $i++) {
                            $dateNew = strtotime('-' . $i . ' MONTH');
                            $months[] = date("Y-m", $dateNew);
                            $months_reverse[] = date("F-Y", $dateNew);
                        }
                        $months = array_reverse($months);
                        $months_reverse = array_reverse($months_reverse);
                        foreach ($months_reverse as $month) {
                            echo "<th>" . $month . "</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody id="list">
                    <?php
                    $query = "SELECT * FROM `Managers`";
                    $result = mysqli_query($link, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['Name'] . " (" . $row['Percent'] . ")" . "</td>";
                        foreach ($months as $month) {
                            $month_start = $month . "-01";
                            $month_end = $month . "-31";
                            $query_month = "SELECT * FROM `Subtype` WHERE `Meneger` = '" . $row['Name'] . "' AND `Status` = 'Завершено' AND `Money_Date` BETWEEN '$month_start' AND '$month_end'";
                            $result_month = mysqli_query($link, $query_month);
                            $b = 0;
                            while ($rowM = mysqli_fetch_array($result_month)) {
                                $query1 = "SELECT `Сумма` FROM `Cost` WHERE `Subtype` = '" . $rowM['SubType'] . "' AND `name` != 'exec' AND `type` = 'capex'";
                                if ($res1 = mysqli_query($link, $query1)) {
                                    while ($row1 = mysqli_fetch_array($res1)) {
                                        $summ1 += (float)$row1['Сумма'];
                                    }
                                } else {
                                    $mess = "Ошибка в сумм1:" . mysqli_error($link);
                                }
                                $query2 = "SELECT `Сумма` FROM `Cost` WHERE `Subtype` = '" . $rowM['SubType'] . "' AND `name` = 'exec'";
                                if ($res2 = mysqli_query($link, $query2)) {
                                    while ($row2 = mysqli_fetch_array($res2)) {
                                        $summ2 += (float)$row2['Сумма'];
                                    }
                                } else {
                                    $mess .= "Ошибка в сумм2:" . mysqli_error($link);
                                }

                                $query3 = "SELECT `Сумма` FROM `Cost` WHERE `Subtype` = '" . $rowM['SubType'] . "' AND `type` = 'revenue'";
                                if ($res3 = mysqli_query($link, $query3)) {
                                    while ($row3 = mysqli_fetch_array($res3)) {
                                        $summ3 += (float)$row3['Сумма'];
                                    }
                                } else {
                                    $mess .= "Ошибка в сумм3:" . mysqli_error($link);
                                }
                                $month_number = 0;
                                if ($rowM['Act_Date'] != "" && $rowM['Money_Date'] != "") {
                                    $bool_check_date = (strtotime($rowM['Act_Date']) < strtotime($rowM['Money_Date']));
                                    if ($bool_check_date == true) {
                                        $date1 = new DateTime($rowM['Act_Date']);
                                        $date2 = new DateTime($rowM['Money_Date']);
                                        $interval = date_diff($date1, $date2);
                                        $days = $interval->d;
                                        $month_number = round($days / 31);
                                    }
                                }
                                if ($month_number == "" || $month_number == null) {
                                    $month_number = 0;
                                }
                                $Credit = ((($summ3 * (18 / 100)) / 12) * $month_number);

                                $fullexpenses = $summ1 + $summ2 + $Credit;
                                $Percent = $row['Percent'];
                                $margin = ($summ3 - $fullexpenses) * 0.48;
                                $prize = $margin * ($Percent / 100);
                                $b += $prize;
                                // echo "<td>$margin</td>";
                                // $b++;
                            }
                            if ($b != '' || $b != null) {
                                echo "<td style='background:#90ee90;'>" . number_format($b, 2, ',', ' ') . "</td>";
                            } else {
                                echo "<td>---</td>";
                            }
                            $total += $b;
                            $b = 0;
                            $summ1 = 0;
                            $summ2 = 0;
                            $summ3 = 0;
                        }
                        echo "<td>" . number_format($total, 2, ',', ' ') . "</td>";
                        echo "<td>---</td>";
                        echo "</tr>";
                        $total = 0;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <script src="../js/menu.js"></script>
        <script src="../js/manager_list.js"></script>
    </body>

    </html>

<?php
} else {
    tologin();
}
?>