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
        <title>Вывод из таблиц</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/Fact.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/nav.css" rel="stylesheet" />

    </head>

    <body>
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <h1>Таблица Fact</h1>
            <div class="tableScroll">
                <table id="fact">
                    <thead>
                        <tr>
                            <?php
                            $querySubType = "SELECT Дата FROM Cost ORDER BY Дата LIMIT 1";
                            $resultSubType = mysqli_query($link, $querySubType);
                            $newrow2 = mysqli_fetch_array($resultSubType);
                            $dateStart = $newrow2['Дата'];

                            $querySubType = "SELECT Дата FROM Cost ORDER BY Дата DESC LIMIT 1";
                            $resultSubType = mysqli_query($link, $querySubType);
                            $newrow2 = mysqli_fetch_array($resultSubType);
                            $dateEnd = $newrow2['Дата'];
                            ?>
                            <th class="col1">Тип</th>
                            <th class="col2">Статьи</th>
                            <th class="col3">Подтип</th>
                            <th class="col4">Имя</th>
                            <th class="col5">Итого c
                                <?php
                                echo $dateStart . " по " . $dateEnd;
                                ?>
                            </th>

                            <?php
                            $dataS = $dateStart;
                            $dataE = $dateEnd;
                            $dataMonths = array();
                            $i = 0;
                            $dataMonths[$i] = date('Y-m', strtotime($dataS));
                            $i++;
                            while (strtotime($dataS) < strtotime($dataE)) {
                                $dataPer = strtotime('+1 MONTH', strtotime($dataS));
                                $dataS = date('Y-m-d', $dataPer);
                                $dataMonths[$i] = date('Y-m', $dataPer);
                                $i++;
                            }
                            foreach ($dataMonths as $dmonth) {
                                echo "<th>" . $dmonth . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $querySubType = "SELECT * FROM Subtype WHERE SubType IN (SELECT DISTINCT Subtype FROM Cost) AND `type`= 'opex'";
                        $resultSubType = mysqli_query($link, $querySubType);
                        $summ = 0;
                        while ($newrow = mysqli_fetch_array($resultSubType)) {
                            echo "<tr>";
                            echo "<td class='opex'>" . $newrow['type'] . "</td>";
                            echo "<td class='opex'>" . $newrow['Name'] . "</td>";
                            echo "<td class='opex'>" . $newrow['SubType'] . "</td>";
                            echo "<td class='opex'>---</td>";
                            $querySUM = "SELECT Сумма FROM Cost WHERE `Subtype` ='" . $newrow['SubType'] . "'";
                            $resultSUM = mysqli_query($link, $querySUM);
                            while ($newrow1 = mysqli_fetch_array($resultSUM)) {
                                $summ += $newrow1['Сумма'];
                            }
                            echo "<td class='money'> " . number_format($summ, 2, ',', ' ')  . "</td>";

                            $summ = 0;
                            foreach ($dataMonths as $dmonth) {
                                $Mstart0 = $dmonth . "-01";
                                $Mend0 = $dmonth . "-31";
                                $query = "SELECT Сумма FROM Cost WHERE `Subtype` ='" . $newrow['SubType'] . "' AND `Дата` BETWEEN '" . $Mstart0 . "' AND '" . $Mend0 . "'";
                                $result = mysqli_query($link, $query);
                                while ($newrowS = mysqli_fetch_array($result)) {
                                    $summMonth += (float)$newrowS['Сумма'];
                                }
                                echo "<td> " . number_format($summMonth, 2, ',', ' ')  . "</td>";
                                $summMonth = 0;
                                $Mstart0 = "";
                                $Mend0 = "";
                            }
                            echo "</tr>";
                        };
                        $queryCapex = "SELECT * FROM Subtype WHERE SubType IN (SELECT DISTINCT Subtype FROM Cost) AND `type`= 'capex'";
                        $resultCapex = mysqli_query($link, $queryCapex);
                        while ($newrowC = mysqli_fetch_array($resultCapex)) {
                            echo "<tr>";
                            echo "<td class='capex'>" . $newrowC['type'] . "</td>";
                            echo "<td class='capex'>" . $newrowC['Name'] . "</td>";
                            echo "<td class='capex'>" . $newrowC['SubType'] . "</td>";
                            echo "<td class='capex'>---</td>";
                            $queryCAP = "SELECT Сумма FROM Cost WHERE `Subtype` ='" . $newrowC['SubType'] . "' AND `type`= 'capex'";
                            $resultCAP = mysqli_query($link, $queryCAP);
                            while ($newrowG = mysqli_fetch_array($resultCAP)) {
                                $summC += (float)$newrowG['Сумма'];
                            }
                            echo "<td class='money'>" . number_format($summC, 2, ',', ' ')  . "</td>";
                            $summC = 0;
                            foreach ($dataMonths as $dmonth) {
                                $MstartC = $dmonth . "-01";
                                $MendC = $dmonth . "-31";
                                $queryCap = "SELECT Сумма FROM Cost WHERE `Subtype` ='" . $newrowC['SubType'] . "' AND `type`= 'capex' AND `Дата` BETWEEN '" . $MstartC . "' AND '" . $MendC . "'";
                                $resultCap = mysqli_query($link, $queryCap);
                                while ($newrowCap = mysqli_fetch_array($resultCap)) {
                                    $summMonthCap += (float)$newrowCap['Сумма'];
                                }
                                echo "<td> " . number_format($summMonthCap, 2, ',', ' ')  . "</td>";
                                $summMonthCap = 0;
                                $Mstart = "";
                                $Mend = "";
                            }

                            echo "</tr>";
                        };
                        $queryRevenue = "SELECT DISTINCT Subtype FROM Cost WHERE `type`= 'revenue'";
                        $resultRevenue = mysqli_query($link, $queryRevenue);
                        while ($newrowR = mysqli_fetch_array($resultRevenue)) {
                            echo "<tr>";
                            echo "<td class='revenue'>revenue</td>";
                            $quRev = "SELECT `Name` FROM Subtype WHERE Subtype = '" . $newrowR['Subtype'] . "'";
                            $resRev = mysqli_query($link, $quRev);
                            while ($rowRev = mysqli_fetch_array($resRev)) {
                                echo "<td class='revenue'>" . $rowRev['Name'] . "</td>";
                            }
                            echo "<td class='revenue'>" . $newrowR['Subtype'] . "</td>";
                            echo "<td class='revenue'>---</td>";
                            $queryRev = "SELECT Сумма FROM Cost WHERE `Subtype` ='" . $newrowR['Subtype'] . "' AND `type`= 'revenue'";
                            $resultRev = mysqli_query($link, $queryRev);
                            while ($newrowG = mysqli_fetch_array($resultRev)) {
                                $summR += (float)$newrowG['Сумма'];
                            }
                            echo "<td class='money'>" . number_format($summR, 2, ',', ' ') . "</td>";
                            $summR = 0;
                            $summMonthR = 0;
                            foreach ($dataMonths as $dmonth) {
                                $Mstart2 = $dmonth . "-01";
                                $Mend2 = $dmonth . "-31";
                                $queryR = "SELECT Сумма FROM Cost WHERE `Subtype` ='" . $newrowR['Subtype'] . "' AND `Дата` BETWEEN '" . $Mstart2 . "' AND '" . $Mend2 . "'";
                                $resultR = mysqli_query($link, $queryR);
                                while ($newrowRe = mysqli_fetch_array($resultR)) {
                                    $summMonthR += (float)$newrowRe['Сумма'];
                                }
                                echo "<td> " . number_format($summMonthR, 2, ',', ' ') . "</td>";
                                $summMonthR = 0;
                                $Mstart = "";
                                $Mend = "";
                            }

                            echo "</tr>";
                        };
                        ?>
                    </tbody>
                </table>
            </div>
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