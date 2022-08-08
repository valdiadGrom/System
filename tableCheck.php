<?php
session_start();
function tologin()
{
    header("Location: index.php");
    exit();
}


if (!empty($_SESSION['auth']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager' || $_SESSION['role'] == 'engineer' || $_SESSION['role'] == 'buyer' || $_SESSION['role'] == 'document_specialist')) {
    include_once "link.php";
    $err = array();
    $success = array();

    if (isset($_POST['submit_menu'])) {
        $name_project = $_POST['hiden_name'];
        $new_status = $_POST['Status_menu'];
        $new_manager = $_POST['manager_menu'];

        if (mysqli_query($link, "UPDATE `Subtype` SET `Meneger`='$new_manager', `Status`='$new_status' WHERE `Name` = '$name_project';")) {
            $success[] = "Данные успешно обновлены";
        } else {
            $err[] = "Проект не был обновлен из за ошибки " . mysqli_error($link);
        }
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Check</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/nav.css" rel="stylesheet" />
        <link href="css/check.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/toast.min.css" rel="stylesheet">

    </head>

    <body onload="Select()">
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <h1>Check</h1>
            <table class="tableCost">
                <thead>
                    <tr>
                        <th rowspan="1">Наименование</th>
                        <th rowspan="1" class="plan">Плановая стоимость оборудования</th>
                        <th rowspan="1" class="plan">Плановая стоимость работ</th>
                        <th rowspan="1" class="plan">Плановые доходы по проекту</th>
                        <th rowspan="1">Фактические затраты на оборудование</th>
                        <th rowspan="1">Фактические затраты на работы</th>
                        <th rowspan="1">Фактические доходы</th>
                        <th rowspan="1">Затраты на привлечение кредита (18% годовых)</th>
                        <th rowspan="1">Полные расходы</th>
                        <th rowspan="1" style="width: 200px;">Итого Маржа</th>
                        <th rowspan="1">Статус</th>
                        <th rowspan="1">Менеджер</th>
                        <th rowspan="1">Премия менеджера</th>
                        <th rowspan="1">Дата заключения договора</th>
                        <th rowspan="1">План/Факт получения денег</th>
                        <th rowspan="1">Дата завершения работ</th>
                        <th rowspan="1">Срок задолженности (мес)</th>
                        <th rowspan="1">Инжинер</th>
                    </tr>
                    <tr>
                        <th>
                            <?php
                            $result_name = mysqli_query($link, "SELECT `Name` FROM `Subtype` WHERE `type` = 'capex';");
                            echo "<select id='Name_sub' class = 'SumoSelect' name = 'Name_sub' onchange='filter_onchange()'>";
                            echo "<option value=''>Не выбрано</option>";
                            while ($row_name = mysqli_fetch_array($result_name)) {
                                echo "<option value = '" . $row_name['Name'] . "' > " . $row_name['Name'] . " </option>";
                            }
                            echo "</select>";
                            ?>
                        </th>
                        <th><select name="plan1" id="plan1" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select></th>
                        <th><select name="plan2" id="plan2" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select></th>
                        <th><select name="plan3" id="plan3" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select></th>
                        <th><select name="fact1" id="fact1" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select></th>
                        <th><select name="fact2" id="fact2" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select></th>
                        <th><select name="fact3" id="fact3" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select></th>
                        <th><select name="credit" id="credit" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select></th>
                        <th><select name="full_expencess" id="full_expencess" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select></th>
                        <th><select name="margin" id="margin" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select></th>
                        <th>
                            <?php
                            $querySelect = "SELECT `StatusD` FROM `Status`";
                            $resultSelect = mysqli_query($link, $querySelect);
                            echo "<select id='StatusD' class = 'SumoSelect' name = 'selectOrg' onchange='filter_onchange()'>";
                            echo "<option value=''>Не выбрано</option>";
                            while ($objectOrg = mysqli_fetch_array($resultSelect)) {
                                echo "<option value = '" . $objectOrg['StatusD'] . "' > " . $objectOrg['StatusD'] . " </option>";
                            }
                            echo "</select>";
                            ?>
                        </th>
                        <th>
                            <?php
                            $result_manager = mysqli_query($link, "SELECT * FROM `Managers`");
                            echo "<select id='Managers' class = 'SumoSelect' name = 'Managers' onchange='filter_onchange()'>";
                            echo "<option value=''>Не выбрано</option>";
                            while ($objectSelect = mysqli_fetch_array($result_manager)) {
                                echo "<option value = '" . $objectSelect['Name'] . "' > " . $objectSelect['Name'] . " </option>";
                            }
                            echo "</select>";
                            ?>
                        </th>
                        <th>
                            <select name="prize" id="prize" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select>
                        </th>
                        <th>
                            <select name="dateStart" id="dateStart" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select>
                        </th>
                        <th>
                            <select name="moneyDate" id="moneyDate" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select>
                        </th>
                        <th>
                            <select name="endDate" id="endDate" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select>
                        </th>
                        <th>
                            <select name="debt" id="debt" class="SumoSelect" onchange="filter_onchange()">
                                <option value="" selected>Не выбрано</option>
                            </select>
                        </th>
                        <th>
                            <?php

                            $result_engineer = mysqli_query($link, "SELECT * FROM `Engineers`");
                            echo "<select id='Engineers' class = 'SumoSelect' name = 'Engineers' onchange='filter_onchange()'>";
                            echo "<option value=''>Не выбрано</option>";
                            while ($rowEng = mysqli_fetch_array($result_engineer)) {
                                echo "<option value = '" . $rowEng['Name'] . "' > " . $rowEng['Name'] . " </option>";
                            }
                            echo "</select>";
                            ?>
                        </th>
                    </tr>
                </thead>
                <tbody id="FilterTable">
                    <?php
                    $queryProject = "SELECT * FROM Subtype WHERE `type` = 'capex'";
                    $resultProject = mysqli_query($link, $queryProject);
                    while ($rowProject = mysqli_fetch_array($resultProject)) {
                        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'engineer' || $_SESSION['role'] == 'buyer') {
                            $href_calc = "tableCalculation.php?nameToCalc=" . $rowProject['id'] . "";
                        } else {
                            $href_calc = "#";
                        }
                        $result_check_calc = mysqli_query($link, "SELECT * FROM `DataFromCalculation` WHERE `tag` = '" . $rowProject['id'] . "';");
                        $count_check = mysqli_num_rows($result_check_calc);
                        if ($count_check == 0) {
                            $result_delete_if_0 = mysqli_query($link, "DELETE FROM `DataFromCalculationAll` WHERE `tag` = '" . $rowProject['id'] . "';");
                        }
                        echo "<tr>";
                        echo "<td><a href='$href_calc'>" . $rowProject['Name'] . "</a></td>";

                        $queryCalc = "SELECT * FROM `DataFromCalculationAll` WHERE `tag` = '" . $rowProject['id'] . "';";
                        $resultCalc = mysqli_query($link, $queryCalc);
                        $countCalc = mysqli_num_rows($resultCalc);
                        if ($countCalc > 0) {
                            while ($rowCalc = mysqli_fetch_array($resultCalc)) {
                                echo "<td class='plan' style='white-space:nowrap;'><a href='$href_calc'>" . number_format($rowCalc['cost_Equipment'], 2, ',', ' ') . "</td>";
                                echo "<td class='plan' style='white-space:nowrap;'><a href='$href_calc'>" . number_format($rowCalc['cost_Work'], 2, ',', ' ') . "</td>";
                                echo "<td class='plan' style='white-space:nowrap;'><a href='$href_calc'>" . number_format($rowCalc['planned_Money'], 2, ',', ' ') . "</td>";
                            }
                        } else {
                            echo "<td class='plan'><a href='$href_calc'>Расчитать</a></td>";
                            echo "<td class='plan'><a href='$href_calc'>Расчитать</a></td>";
                            echo "<td class='plan'><a href='$href_calc'>Расчитать</a></td>";
                        }

                        $query1 = "SELECT Сумма FROM Cost WHERE Subtype = '" . $rowProject['SubType'] . "' AND name != 'exec' AND `type` = 'capex'";
                        $res1 = mysqli_query($link, $query1);
                        while ($row1 = mysqli_fetch_array($res1)) {
                            $summ1 += (float)$row1['Сумма'];
                        }
                        echo "<td class='fact' style='white-space:nowrap;'>" . number_format($summ1, 2, ',', ' ') . "</td>";

                        $query2 = "SELECT Сумма FROM Cost WHERE Subtype = '" . $rowProject['SubType'] . "' AND name = 'exec'";
                        $res2 = mysqli_query($link, $query2);
                        while ($row2 = mysqli_fetch_array($res2)) {
                            $summ2 += (float)$row2['Сумма'];
                        }
                        echo "<td class='fact' style='white-space:nowrap;'>" . number_format($summ2, 2, ',', ' ') . "</td>";

                        $query3 = "SELECT Сумма FROM Cost WHERE Subtype = '" . $rowProject['SubType'] . "' AND `type` = 'revenue'";
                        $res3 = mysqli_query($link, $query3);
                        while ($row3 = mysqli_fetch_array($res3)) {
                            $summ3 += (float)$row3['Сумма'];
                        }
                        echo "<td class='fact' style='white-space:nowrap;'>" . number_format($summ3, 2, ',', ' ') . "</td>";
                        $month_number = 0;
                        if ($rowProject['Act_Date'] != "" && $rowProject['Money_Date'] != "") {
                            $bool_check_date = (strtotime($rowProject['Act_Date']) < strtotime($rowProject['Money_Date']));
                            if ($bool_check_date == true) {
                                $date1 = new DateTime($rowProject['Act_Date']);
                                $date2 = new DateTime($rowProject['Money_Date']);
                                $interval = date_diff($date1, $date2);
                                $days = $interval->d;
                                $month_number = round($days / 31);
                            }
                        }
                        $Credit = ((($summ3 * (18 / 100)) / 12) * $month_number);
                        echo "<td style='white-space:nowrap;'>" . number_format($Credit, 2, ',', ' ') . "</td>";

                        $fullexpenses = $summ1 + $summ2 + $Credit;
                        echo "<td style='white-space:nowrap;'>" . number_format($fullexpenses, 2, ',', ' ') . "</td>";

                        $margin = ($summ3 - $fullexpenses) * 0.48;
                        echo "<td style='white-space:nowrap;'>" . number_format($margin, 2, ',', ' ') . "</td>";

                        $status_colors = array('Завершено' => "#90ee90", 'Отказ' => "red", 'Работы выполняются' => "#b38dd9", 'КП на рассмотрении клиента' => "pink", 'Ожидается оплата' => "#42aaff");


                        if ($rowProject['Status'] == null) {
                            echo "<td style='background: yellow; white-space:nowrap;'>Не установлено</td>";
                        } else {
                            echo "<td style='background: " . $status_colors[$rowProject['Status']] . ";'>" . $rowProject['Status'] . "</td>";
                        }


                        $Manager = $rowProject['Meneger'];
                        if ($Manager == null) {
                            $Manager = "Не поставлен";
                        }
                        $Manager = preg_replace('/\s+/', ' ', $Manager);

                        echo "<td style='white-space:nowrap;'>" . $Manager . "</td>";

                        if ($Manager == "Не поставлен") {
                            echo "<td style='white-space:nowrap;'>Нет менеджера</td>";
                        } else {
                            $query5 = "SELECT * FROM `Managers` WHERE `Name` = '" . $Manager . "'";
                            $res5 = mysqli_query($link, $query5);
                            while ($row5 = mysqli_fetch_array($res5)) {
                                $Percent = $row5['Percent'];
                                $name = $row5['Name'];
                            }
                            if ($Manager == $name) {
                                $prize = $margin * ($Percent / 100);
                                echo "<td style='white-space:nowrap;'>" . number_format($prize, 2, ',', ' ') . "</td>";
                            } else {
                                echo "<td style='white-space:nowrap;'>Менеджер не совпадает</td>";
                            }
                        }
                        if ($_SESSION['role'] == 'admin') {
                            $href_proj = "newProject.php?Name=" . $rowProject['id'] . "";
                        } else {
                            $href_proj = "#";
                        }

                        if ($rowProject['Start_Date'] == "") {
                            echo "<td class='errors' style='white-space:nowrap;'><a href = '$href_proj'>Изменить</a></td>";
                        } else {
                            echo "<td style='white-space:nowrap;'><a href = '$href_proj'>" . $rowProject['Start_Date'] . "</td>";
                        }
                        if ($rowProject['Money_Date'] == "") {
                            echo "<td class='errors' style='white-space:nowrap;'><a href = '$href_proj'>Изменить</a></td>";
                        } else {
                            echo "<td style='white-space:nowrap;'><a href = '$href_proj'>" . $rowProject['Money_Date'] . "</td>";
                        }
                        if ($rowProject['Act_Date'] == "") {
                            echo "<td class='errors' style='white-space:nowrap;'><a href = '$href_proj'>Изменить</a></td>";
                        } else {
                            echo "<td style='white-space:nowrap;'><a href = '$href_proj'>" . $rowProject['Act_Date'] . "</td>";
                        }

                        if ($rowProject['Act_Date'] != "" && $rowProject['Money_Date'] != "") {
                            echo "<td style='white-space:nowrap;'>$month_number</td>";
                        } else {
                            echo "<td style='white-space:nowrap;'>Нет данных</td>";
                        }

                        if ($rowProject['Engineer'] == "") {
                            echo "<td class='errors' style='white-space:nowrap;'><a href = '$href_proj'>Изменить</a></td>";
                        } else {
                            echo "<td style='white-space:nowrap;'>" . $rowProject['Engineer'] . "</td>";
                        }
                        echo "</tr>";
                        $summ1 = 0;
                        $summ2 = 0;
                        $summ3 = 0;
                        $query4 = "";
                        $res4 = null;
                    }
                    ?>


                </tbody>
            </table>
            <?php
            if ($_SESSION['role'] == 'admin') {
            ?>
                <div id="zatemnenie">
                    <window> <a id="closeX" class="close_window">X</a>
                        <form method="post" id="form_menu"></form>
                        <h1 hidden><input type="text" id="hiden_name" name="hiden_name" form="form_menu"></h1>
                        Изменение проекта <label id="tyt" style="color: blue;"></label>
                        <p style="text-align: left;">Статус: </p>
                        <?php
                        $result_menu = mysqli_query($link, "SELECT `StatusD` FROM `Status`");
                        echo "<select id='Status_menu' class = 'Status_menu' name = 'Status_menu' form='form_menu'>";
                        echo "<option value=''>Не установлено</option>";
                        while ($objM = mysqli_fetch_array($result_menu)) {
                            echo "<option value = '" . $objM['StatusD'] . "' > " . $objM['StatusD'] . " </option>";
                        }
                        echo "</select>";
                        ?>
                        <p style="text-align: left;">Менеджер:</p>
                        <?php
                        $result_manager_menu = mysqli_query($link, "SELECT * FROM `Managers`");
                        echo "<select id='manager_menu' class = 'manager_menu' name = 'manager_menu' form='form_menu'>";
                        echo "<option value=''>Не выбрано</option>";
                        while ($row_man = mysqli_fetch_array($result_manager_menu)) {
                            echo "<option value = '" . $row_man['Name'] . "' > " . $row_man['Name'] . " </option>";
                        }
                        echo "</select>";
                        ?>
                        <input type="submit" value="Сохранить" id="submit_menu" name="submit_menu" form='form_menu'>
                    </window>
                </div>
            <?php
            }
            ?>
        </div>
        <script src="js/jquery-3.6.0.min.js"></script>
        <script src="js/jquery.sumoselect.js"></script>
        <script src="js/check_filter.js"></script>
        <script src="js/toast.min.js"></script>
        <script src="js/menu.js"></script>
        <script src="js/Check_select.js"></script>
        <script>
            $(document).ready(function() {
                $('.SumoSelect').SumoSelect({
                    search: true,
                });
                $('.Status_menu').SumoSelect({
                    search: true,
                });
                $('.manager_menu').SumoSelect({
                    search: true,
                });
            })


            let table_filter = document.getElementById("FilterTable");
            let closeX = document.getElementById("closeX");
            let modal_window = document.getElementById("zatemnenie");
            let tyt = document.getElementById("tyt");
            let hiden_name = document.getElementById("hiden_name");
            let tr_table;
            let select_status = document.getElementById("Status_menu");
            let select_manager = document.getElementById("manager_menu");

            table_filter.addEventListener("dblclick", function(event) {

                tr_table = event.target.closest("tr");
                if (!tr_table) return;
                tyt.textContent = tr_table.cells[0].textContent;
                hiden_name.value = tr_table.cells[0].textContent;
                modal_window.style = "display:block";
                let exam = tr_table.cells[10].textContent;
                let manager_selected = tr_table.cells[11].textContent;
                $("select.Status_menu")[0].sumo.selectItem(exam);
                $("select.manager_menu")[0].sumo.selectItem(manager_selected);
            });

            closeX.addEventListener("click", function(func) {
                modal_window.style = "display:none";
                $("select.Status_menu")[0].sumo.selectItem(0);
                $("select.manager_menu")[0].sumo.selectItem(0);
            })
        </script>
        <?php
        foreach ($err as $error) {
            echo '<script> new Toast({ 
                title: "Ошибка",
                text: "' . $error . '",
                theme: "danger",
                autohide: true,
                interval: 10000,
              });</script>';
        }
        foreach ($success as $succ) {
            echo '<script> new Toast({ 
                title: "Успешно",
                text: "' . $succ . '",
                theme: "success",
                autohide: true,
                interval: 10000,
            });</script>';
        }
        ?>
    </body>

    </html>
<?php
} else {
    tologin();
}
?>