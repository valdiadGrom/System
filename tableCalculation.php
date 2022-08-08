<?php
session_start();
function tologin()
{
    header("Location: index.php");
    exit();
}

function check_post($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = addslashes($data);
    return $data;
}

function returnName($tag)
{
    $host = '81.90.180.80:3316';
    $user = 'profit';
    $password = 'Profit2018';
    $db_name = 'profiteng';
    $link = mysqli_connect($host, $user, $password, $db_name);

    if ($link === false) {
        die("Ошибка: " . mysqli_connect_error());
    }
    $queryFromSQL = "SELECT * FROM `Subtype` WHERE `id` = '$tag' LIMIT 1;";
    $resultFromSQL = mysqli_query($link, $queryFromSQL);
    while ($rowSQL = mysqli_fetch_array($resultFromSQL)) {
        $tagName = $rowSQL['Name'];
    }
    return $tagName;
}

if (!empty($_SESSION['auth']) && ($_SESSION['role'] == 'admin')) {
    include_once "link.php";
    $errors = array();
    $success = array();
    if (isset($_GET['nameToCalc'])) {
        $subtype = check_post($_GET['nameToCalc']);
        $Name = returnName($subtype);
    }
    if (isset($_SESSION['upload_calc_succ']) || isset($_SESSION['upload_calc_err'])) {
        $success[] = $_SESSION['upload_calc_succ'];
        if (isset($_SESSION['upload_calc_err'])) {
            $err_from_upload = $_SESSION['upload_calc_err'];
            foreach ($err_from_upload as $err_up) {
                $errors[] = $err_up;
            }
        }
        unset($_SESSION['upload_calc_succ']);
        unset($_SESSION['upload_calc_err']);
    }

    if (isset($_POST['del'])) {
        $name_to_del = htmlspecialchars($_POST['del']);
        $tag_to_del = htmlspecialchars($_POST['tag']);
        $sql = mysqli_query($link, "DELETE FROM `DataFromCalculation` WHERE `tag` = '$tag_to_del' AND `Наименование` = '" . $name_to_del . "';");
        if ($sql) {
            $success[] = "Удалена строка: " . $name_to_del;
        } else {
            $errors[] = "Произошла ошибка: " . mysqli_error($link);
        }
    }
    if (isset($_POST['load'])) {
        for ($i = 0; $i < count($_POST['NameInp']); $i++) {
            $namePost[] = check_post($_POST['NameInp'][$i]);
            $units[] = check_post($_POST['EdIzm'][$i]);
            $quantity[] = (int)check_post($_POST['koll'][$i]);
            $replacement[] = (float)check_post($_POST['Zamen'][$i]);
            $timePost[] = (float)check_post($_POST['time'][$i]);
            $provider1[] = (float)check_post($_POST['GaniMed'][$i]);
            $provider2[] = (float)check_post($_POST['ETM'][$i]);
            $provider3[] = (float)check_post($_POST['Citil'][$i]);
            $provider4[] = (float)check_post($_POST['bolid'][$i]);
            $provider5[] = (float)check_post($_POST['bolid'][$i]); // Добавить !!!!!!
            $urlPost[] = check_post($_POST['urlSale'][$i]);
        }

        if (isset($_POST['checklog'])) {
            $logistic = (float)check_post($_POST['logistic']);
        } else {
            $logistic = 0;
        }

        if (isset($_POST['checkKomand'])) {
            $business_trips = (float)check_post($_POST['komandir']);
        } else {
            $business_trips = 0;
        }

        if (isset($_POST['checkpusk'])) {
            $commissioning = (float)check_post($_POST['pusk']);
        } else {
            $commissioning = 0;
        }
        $percent10 = (int)check_post(($_POST['percent10']));

        $percent_Equipment = (int)check_post($_POST['percent_Equipment']);
        $percentWork = (int)check_post($_POST['percentWork']);

        $cost_Equipment = (float)check_post($_POST['cost_Equipment']);
        $cost_Work = (float)check_post($_POST['cost_Work']);
        $planned_Money = (float)check_post($_POST['planned_Money']);

        $risk_period = (int)check_post($_POST['risk_period']);
        $weekends = (int)check_post($_POST['weekends']);
        $number_workers = (int)check_post($_POST['number_workers']);
        $distance_Object = (int)check_post($_POST['distance_Object']);
        $price_gasoline = (float)check_post($_POST['price_gasoline']);
        $expense = (float)check_post($_POST['expense']);
        $cost_per_month = (float)check_post($_POST['cost_per_month']);
        $cost_per_day = (float)check_post($_POST['cost_per_day']);
        $daily_person = (float)check_post($_POST['daily_person']);

        $subtypeNew = check_post($_POST['subtype']);

        if (isset($_GET['nameToCalc'])) {
            $duplicates = array_unique(array_diff_assoc($namePost, array_unique($namePost)));
            if (count($duplicates) == 0) {
                for ($i = 0; $i < count($_POST['NameInp']); $i++) {
                    $queryCheck = "SELECT * FROM `DataFromCalculation` WHERE `tag` = '$subtypeNew' AND `Наименование` = '$namePost[$i]';";
                    $resultCheck = mysqli_query($link, $queryCheck);
                    $count = mysqli_num_rows($resultCheck);

                    if ($count == 1) {
                        $change = $namePost[$i] . " были обновлены";
                        $count_update++;
                        $queryFromCalc = "UPDATE `DataFromCalculation` SET `units`= '$units[$i]', `quantity`= '$quantity[$i]', `replacement`= '$replacement[$i]', `timing` = '$timePost[$i]', `provider1` = '$provider1[$i]', `provider2` = '$provider2[$i]', `provider3` = '$provider3[$i]', `provider4` = '$provider4[$i]', `provider5` = '$provider5[$i]', `link` = '$urlPost[$i]' WHERE `tag` = '$subtypeNew' AND `Наименование` = '$namePost[$i]';";
                    } elseif ($count > 1) {
                        $errors[] = "Ошибка при добавлении. Объект $namePost[$i] уже присутствует в базе данных более одного раза.";
                    } else {
                        $change = $namePost[$i] . " были добавлены";
                        $count_add++;
                        $queryFromCalc = "INSERT INTO `DataFromCalculation` (`tag`, `Наименование`, `units`, `quantity`, `replacement`, `timing`, `provider1`, `provider2`, `provider3`, `provider4`, `provider5`, `link`) VALUES ('$subtypeNew', '$namePost[$i]', '$units[$i]', '$quantity[$i]', '$replacement[$i]', '$timePost[$i]', '$provider1[$i]', '$provider2[$i]', '$provider3[$i]', '$provider4[$i]', '$provider5[$i]', '$urlPost[$i]');";
                    }
                    if (isset($queryFromCalc)) {
                        if (mysqli_query($link, $queryFromCalc)) {
                            // $success[] = "Данные обьекта " . $change . " Время: " . $timePost[$i];
                            // $errors[] = $queryFromCalc;
                            $count_success++;
                        } else {
                            $errors[] = "Данные обьекта $namePost[$i] не были добавлены из-за ошибки " . mysqli_error($link);
                        }
                    }
                }
                if ($count_update > 0) $success[] = "Обновлено $count_update строк";
                if ($count_add > 0) $success[] = "Добавлено $count_add строк";
                if ($count_add + $count_update == $count_success) {
                    $success[] = "Все строки успешно изменены";
                    $queryCheckAll = "SELECT * FROM `DataFromCalculationAll` WHERE `tag`= '$subtypeNew';";
                    $resultCheckAll = mysqli_query($link, $queryCheckAll);
                    $countAll = mysqli_num_rows($resultCheckAll);
                    if ($countAll > 0) {
                        $successEx = " обновлены";
                        $queryToCalcAll = "UPDATE `DataFromCalculationAll` SET `percent_Equipment` = '$percent_Equipment', `percentWork` = '$percentWork', `timingAll` = '0', `logistics` = '$logistic', `business_trips` = '$business_trips', `commissioning` = '$commissioning', `percent10` = '$percent10', `cost_Equipment` = '$cost_Equipment', `cost_Work` = '$cost_Work', `planned_Money` = '$planned_Money', `risk_period` = '$risk_period', `weekends` = '$weekends', `number_workers` = '$number_workers', `distance_Object` = '$distance_Object', `price_gasoline` = '$price_gasoline', `expense` = '$expense', `cost_per_month` = '$cost_per_month', `cost_per_day` = '$cost_per_day', `daily_person` = '$daily_person' WHERE `tag`= '$subtypeNew';";
                    } else {
                        $successEx = " добавлены";
                        $queryToCalcAll = "INSERT INTO `DataFromCalculationAll` (`tag`, `percent_Equipment`, `percentWork`, `timingAll`, `logistics`, `business_trips`, `commissioning`, `percent10`, `cost_Equipment`, `cost_Work`, `planned_Money`, `risk_period`, `weekends`, `number_workers`, `distance_Object`, `price_gasoline`, `expense`, `cost_per_month`, `cost_per_day`, `daily_person`) VALUES ('$subtypeNew', '$percent_Equipment', '$percentWork', '228', '$logistic', '$business_trips', '$commissioning', '$percent10', '$cost_Equipment', '$cost_Work', '$planned_Money', '$risk_period', '$weekends', '$number_workers', '$distance_Object', '$price_gasoline', '$expense', '$cost_per_month', '$cost_per_day', '$daily_person');";
                    }
                    if (mysqli_query($link, $queryToCalcAll)) {
                        $success[] = "Данные нижней таблицы успешно" . $successEx;
                    } else {
                        $errors[] = "Данные расчета таблицы не были" . $successEx . " из-за ошибки " . mysqli_error($link);
                    }
                } else {
                    $errors[] = "Ошибок при добавлении: " . ($count_success - ($count_add + $count_update));
                }
            } else {
                $errors[] = "В данных присутствуют дубликаты. Изменения не были сохранены.";
            }
        } else {
            $errors[] = "Не выбран активный проект для расчетов";
        }
    }

?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Calculation</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/nav.css" rel="stylesheet" />
        <!-- <link href="css/style.css" rel="stylesheet" /> -->
        <link href="css/calc.css" rel="stylesheet" />
        <link href="css/newProject.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/toast.min.css" rel="stylesheet">
    </head>

    <body onload="Calc()">
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <h1>Расчет рентабельности проекта:</h1>
            <?php
            echo "<h1>$Name</h1>";
            if (isset($_POST['subtype'])) {
                echo "<h1 hidden><input type='text' form='form' name='subtype' value='$subtypeNew'></h1>";
            } else {
                echo "<h1 hidden><input type='text' form='form' name='subtype' value='$subtype'></h1>";
            }
            ?>
            <button id="tocheck" class="button_calc" onclick="setTimeout(function(){document.location.href = 'tableCheck.php';},500);">Вернутся в таблицу</button>
            <div id="load_div">
                <form method="POST" action="upload_calc.php" enctype="multipart/form-data">
                    <span>Загрузите файл:</span>
                    <input type="file" class="button_calc" name="uploadedFile" />
                    <?php
                    echo '<input type="hidden" name="hidden_id" value="' . $_GET['nameToCalc'] . '">';
                    ?>
                    <input type="submit" id="load_exel" class="button_calc" name="uploadBtn" value="Загрузить" />
                </form>
            </div>

            <div class="NewP">
                <button id="buttonAdd" onclick="addRow()">Добавить строку</button>
                <form method="POST" id="form"></form>
                <table class="tableCalc" id="tableCalc">
                    <thead id="thead">
                        <tr>
                            <th rowspan="2" colspan="2" style="width: 200px;" class="firstcolor">Наименование</th>
                            <th rowspan="2" class="firstcolor">Ед.Изм.</th>
                            <th rowspan="2" class="firstcolor">Количество</th>
                            <th rowspan="2" class="firstcolor">Цена оборудования</th>
                            <th rowspan="2" class="firstcolor">Стоимость оборудования</th>
                            <th rowspan="2" class="firstcolor">Цена работы</th>
                            <th rowspan="2" class="firstcolor">Стоимость работы</th>
                            <th rowspan="1" colspan="2" class="secondcolor">Цена оборудования</th>
                            <th rowspan="2" class="secondcolor">Стоимость закупки</th>
                            <th rowspan="1" class="secondcolor">Цена работ</th>
                            <th rowspan="2" class="secondcolor">Стоимость работ</th>
                            <th rowspan="2" class="next">Замена</th>
                            <th rowspan="1" class="thirdcolor">Тайминг</th>
                            <th rowspan="2" class="thirdcolor">Итого время</th>
                            <th colspan="6" class="grey">Поставщики</th>
                            <th rowspan="2" class="next">Ссылка</th>
                        </tr>
                        <tr>
                            <?php
                            if (isset($_GET['nameToCalc']) || empty($subtypeNew) == false) {
                                if (isset($_POST['subtype'])) {
                                    $tagValue = $subtypeNew;
                                } else {
                                    $tagValue = check_post($_GET['nameToCalc']);
                                }
                                $queryDownloadFromSql = "SELECT * FROM `DataFromCalculationAll` WHERE `tag` = '$tagValue';";
                                $resuldDown = mysqli_query($link, $queryDownloadFromSql);
                                $countDataAll = mysqli_num_rows($resuldDown);
                                if ($countDataAll > 0) {
                                    while ($rowDAll = mysqli_fetch_array($resuldDown)) {
                                        $percent_EquipmentNew = $rowDAll['percent_Equipment'];
                                        if (is_float($percent_EquipmentNew) || $percent_EquipmentNew <= 1) {
                                            $percent_EquipmentNew = $percent_EquipmentNew * 100;
                                        }
                                        $percentWorkNew = $rowDAll['percentWork'];
                                        if (is_float($percentWorkNew) || $percentWorkNew <= 1) {
                                            $percentWorkNew = $percentWorkNew * 100;
                                        }
                                        $logisticsNew = $rowDAll['logistics'];
                                        $business_tripsNew = $rowDAll['business_trips'];
                                        $commissioningNew = $rowDAll['commissioning'];
                                        $percent10New = $rowDAll['percent10'];
                                        $cost_EquipmentNew = $rowDAll['cost_Equipment'];
                                        $cost_WorkNew = $rowDAll['cost_Work'];
                                        $planned_MoneyNew = $rowDAll['planned_Money'];
                                        $risk_periodNew = $rowDAll['risk_period'];
                                        $weekendsNew = $rowDAll['weekends'];
                                        $number_workersNew = $rowDAll['number_workers'];
                                        $distance_ObjectNew = $rowDAll['distance_Object'];
                                        $price_gasolineNew = $rowDAll['price_gasoline'];
                                        $expenseNew = $rowDAll['expense'];
                                        $cost_per_monthNew = $rowDAll['cost_per_month'];
                                        $cost_per_dayNew = $rowDAll['cost_per_day'];
                                        $daily_personNew = $rowDAll['daily_person'];
                                    }
                                }
                                echo '<th class="secondcolor"><input style="width: 50%;" class="secondcolor" name="percent_Equipment" id="percentPrice" type="text" value="' . $percent_EquipmentNew . '" style="text-align: center;" form="form" onchange="Calc()">%</th>';
                            } else {
                                echo '<th class="secondcolor"><input style="width: 50%;" class="secondcolor" name="percent_Equipment" id="percentPrice" type="text" value="30" style="text-align: center;" form="form" onchange="Calc()">%</th>';
                            }
                            ?>
                            <th class="secondcolor">Выбор?</th>
                            <?php
                            if ($countDataAll > 0) {
                                echo '<th class="secondcolor"><input style="width: 50%;" name="percentWork" id="PercentWork" class="secondcolor" type="text" value="' . $percentWorkNew . '" style="text-align: center;" form="form" onchange="Calc()">%</th>';
                            } else {
                                echo '<th class="secondcolor"><input style="width: 50%;" name="percentWork" id="PercentWork" class="secondcolor" type="text" value="100" style="text-align: center;" form="form" onchange="Calc()">%</th>';
                            }
                            ?>
                            <th class="thirdcolor"><input type="text" name="timeNumber" class="thirdcolor" id="timeNumber" value="8.34" onchange="Calc()"></th>
                            <th class="grey">Мин</th>
                            <th style="width: 75px;" class="grey"><input type="text" value="Ганимед" form="form" class="grey"></th>
                            <th style="width: 75px;" class="grey"><input type="text" value="ЭТМ Пенза" form="form" class="grey"></th>
                            <th style="width: 75px;" class="grey"><input type="text" value="Ситилинк" form="form" class="grey"></th>
                            <th style="width: 75px;" class="grey"><input type="text" value="Болид" form="form" class="grey"></th>
                            <th class="grey">Макс</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php
                        if (isset($_GET['nameToCalc']) || isset($_POST['subtype'])) {

                            $queryLoadData = "SELECT * FROM `DataFromCalculation` WHERE `tag` = '$tagValue';";
                            $resultLoadData = mysqli_query($link, $queryLoadData);
                            $countData = mysqli_num_rows($resultLoadData);
                            if ($countData > 0) {
                                while ($rowData = mysqli_fetch_array($resultLoadData)) {
                                    echo "<tr>";
                                    // echo "<th><a href='?nameToCalc=" . $tagValue . "&del=" . $rowData['Наименование'] . "&tag=" . $rowData['tag'] . "'>Удалить</a></th>";
                                    echo "<th><form method='post'>
                                        <input type='hidden' name='del' value='" . ($rowData['Наименование']) . "' />
                                        <input type='hidden' name='tag' value='" . ($rowData['tag']) . "' />
                                        <input type='submit' value='Удалить'>
                                    </form></th>";
                                    echo '<th><input class="inp" type="text" name="NameInp[]" form="form" value="' . ($rowData['Наименование']) . '" placeholder="Наименование" required></th>';
                                    echo '<th><input class="inp" type="text" name="EdIzm[]" form="form" value="' . ($rowData['units']) . '" placeholder="Ед.Изм" required></th>';
                                    echo '<th><input class="inp" type="number" min="1" name="koll[]" form="form" value="' . ($rowData['quantity']) . '" placeholder="Количество" onchange="Calc()" required></th>';
                                    echo '<th>---</th>';
                                    echo '<th>---</th>';
                                    echo '<th>---</th>';
                                    echo '<th>---</th>';
                                    echo '<th colspan="2">---</th>';
                                    echo '<th>---</th>';
                                    echo '<th>---</th>';
                                    echo '<th>---</th>';
                                    echo '<th><input class="inp" type="number" step="0.01" name="Zamen[]" form="form" value="' . ($rowData['replacement']) . '" placeholder="Замена" onchange="Calc()"></th>';
                                    echo '<th><input class="inp" type="number" step="0.01" name="time[]" form="form" value="' . $rowData['timing'] . '" placeholder="Время" onchange="Calc()" required></th>';
                                    echo '<th>---</th>';
                                    echo '<th>---</th>';
                                    echo '<th><input class="inp" type="number" step="0.01" name="GaniMed[]" form="form" value="' . ($rowData['provider1']) . '" onchange="Calc()"></th>';
                                    echo '<th><input class="inp" type="number" step="0.01" name="ETM[]" form="form" value="' . ($rowData['provider2']) . '" onchange="Calc()"></th>';
                                    echo '<th><input class="inp" type="number" step="0.01" name="Citil[]" form="form" value="' . ($rowData['provider3']) . '" onchange="Calc()"></th>';
                                    echo '<th><input class="inp" type="number" step="0.01" name="bolid[]" form="form" value="' . ($rowData['provider4']) . '" onchange="Calc()"></th>';
                                    echo '<th>---</th>';
                                    echo '<th><input class="inp" type="text" name="urlSale[]" form="form" value="' . ($rowData['link']) . '" placeholder="Коментарий"></th>';
                                    echo "</tr>";
                                }
                            } else {


                        ?>
                                <tr>
                                    <th><a href="#" onclick="deleteRow(this)">Удалить</a></th>
                                    <th><input class="inp" type="text" name="NameInp[]" form="form" placeholder="Наименование" required></th>
                                    <th><input class="inp" type="text" name="EdIzm[]" form="form" placeholder="Ед.Изм" required></th>
                                    <th><input class="inp" type="number" min="1" name="koll[]" form="form" placeholder="Количество" onchange="Calc()" required></th>
                                    <th>---</th>
                                    <th>---</th>
                                    <th>---</th>
                                    <th>---</th>
                                    <th colspan="2">---</th>
                                    <th>---</th>
                                    <th>---</th>
                                    <th>---</th>
                                    <th><input class="inp" type="number" step="0.01" name="Zamen[]" form="form" value="0" placeholder="Замена" onchange="Calc()"></th>
                                    <th><input class="inp" type="number" step="0.01" name="time[]" form="form" value="0" placeholder="Время" onchange="Calc()" required></th>
                                    <th>---</th>
                                    <th>---</th>
                                    <th><input class="inp" type="number" step="0.01" name="GaniMed[]" form="form" value="0" onchange="Calc()"></th>
                                    <th><input class="inp" type="number" step="0.01" name="ETM[]" form="form" value="0" onchange="Calc()"></th>
                                    <th><input class="inp" type="number" step="0.01" name="Citil[]" form="form" value="0" onchange="Calc()"></th>
                                    <th><input class="inp" type="number" step="0.01" name="bolid[]" form="form" value="0" onchange="Calc()"></th>
                                    <th>---</th>
                                    <th><input class="inp" type="url" name="urlSale[]" form="form" placeholder="https"></th>
                                </tr>

                        <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tr>
                        <th>---</th>
                        <th style="width: 200px;">Логистика</th>
                        <?php
                        if ($countDataAll > 0 && $logisticsNew > 0) {
                            echo '<td><input type="checkbox" name="checklog" id="checklog" value="1" form="form" style="width: 60%;" onchange="Calc()" checked></td>';
                            echo '<td><input type="number" step="0.01" name="logistic" id="logistic" value="' . $logisticsNew . '" form="form" onchange="Calc()"></td>';
                        } else {
                            echo '<td><input type="checkbox" name="checklog" id="checklog" value="1" form="form" style="width: 60%;" onchange="Calc()"></td>';
                            echo '<td><input type="number" step="0.01" name="logistic" id="logistic" value="0" form="form" onchange="Calc()" disabled></td>';
                        }
                        ?>
                        <td colspan="10"></td>
                        <th>Минут</th>
                        <th id="minTime"></th>
                        <td colspan="7"></td>
                    </tr>
                    <tr>
                        <th>---</th>

                        <th>Командировочные</th>
                        <?php
                        if ($countDataAll > 0 && $business_tripsNew > 0) {
                            echo '<td><input type="checkbox" name="checkKomand" id="checkKomand" value="1" form="form" style="width: 60%;" onchange="Calc()" checked></td>';
                            echo '<td><input type="number" step="0.01" name="komandir" id="komandir" value="' . $business_tripsNew . '" form="form" onchange="Calc()" readonly></td>';
                        } else {
                            echo '<td><input type="checkbox" name="checkKomand" id="checkKomand" value="1" form="form" style="width: 60%;" onchange="Calc()"></td>';
                            echo '<td><input type="number" step="0.01" name="komandir" id="komandir" value="0" form="form" onchange="Calc()" disabled readonly></td>';
                        }
                        ?>
                        <td colspan="10"></td>
                        <th>Часов</th>
                        <th id="hourTime"></th>
                        <td colspan="7"></td>
                    </tr>
                    <tr>
                        <th>---</th>

                        <th style="width: 200px;">Пуско-наладочные</th>
                        <?php
                        if ($countDataAll > 0 && $commissioningNew > 0) {
                            echo '<td><input type="checkbox" name="checkpusk" id="checkpusk" value="1" form="form" style="width: 60%;" onchange="Calc()" checked></td>';
                            echo '<td><input type="number" step="0.01" name="pusk" id="pusk" form="form" value="' . $commissioningNew . '" readonly></td>';
                            echo '<td><input type="number" name="percent10" id="percent10" value="' . $percent10New . '" form="form" style="width: 50%;" onchange="Calc()">%</td>';
                        } else {
                            echo '<td><input type="checkbox" name="checkpusk" id="checkpusk" value="1" form="form" style="width: 60%;" onchange="Calc()"></td>';
                            echo '<td><input type="number" step="0.01" name="pusk" id="pusk" form="form" disabled></td>';
                            echo '<td><input type="number" name="percent10" id="percent10" value="10" form="form" style="width: 50%;" onchange="Calc()" disabled>%</td>';
                        }
                        ?>
                        <td></td>
                        <td id="work10%">---</td>
                        <td colspan="7"></td>
                        <td>Дней на 1 человека</td>
                        <td id="day1"></td>
                        <td colspan="7"></td>
                    </tr>
                    <tr>
                        <th>---</th>

                        <th style="width: 200px;">Итого Оброрудование и материалы</th>
                        <td colspan="3"></td>
                        <td id="ItogoOB">---</td>
                        <td></td>
                        <td id="ItogoWork">---</td>
                        <td colspan="2"></td>
                        <td><input type="text" name="cost_Equipment" style="background-color: yellow;" id="zakAll" form="form" readonly></td>
                        <td></td>
                        <td><input type="text" name="cost_Work" style="background-color: yellow;" id="work100All" form="form" readonly></td>
                        <td colspan="1"></td>
                        <td id="dayN">Дней на несколько</td>
                        <td id="dayAny" style="background-color: yellow;"></td>
                        <td colspan="7"></td>
                    </tr>
                    <tr>
                        <th>---</th>

                        <th style="width: 200px;">Итого</th>
                        <td colspan="5"></td>
                        <th><input type="number" step="0.01" name="planned_Money" style="background-color: yellow;" id="ItogoAll" value="0" form="form" readonly></th>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <th id="itogoStoim">---</th>
                        <td colspan="10"></td>
                    </tr>
                </table>
                <input type="submit" class="button_calc" name="load" form="form" value="Сохранить" onclick="savePost()">
                <br>
                <br>
                <br>
                <table class="tableKomand">
                    <tr>
                        <td class="bottomtwo">Дата начала</td>
                        <?php
                        $queryDate = "SELECT * FROM `Subtype` WHERE `SubType` = '$subtype';";
                        $resultDate = mysqli_query($link, $queryDate);
                        while ($rowD = mysqli_fetch_array($resultDate)) {
                            $Start_Date = $rowD['Start_Date'];
                            $End_Date = $rowD['End_Date'];
                        }
                        if ($Start_Date == "") {
                            $Start_Date = "2020-01-01";
                        }
                        echo '<td><input type="date" name="dateN" id="dateN" form="form" value="' . $Start_Date . '" onchange="Calc()"></td>';
                        ?>

                        <td colspan="2" id="test"></td>
                        <td class="bottomtwo">Проезд на бригаду 4 чел/км</td>
                        <td id="proezd4">---</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Дата окончания</td>
                        <?php
                        echo '<td><input type="date" name="dateF" id="dateF" form="form" value="' . $End_Date . '" onchange="Calc()"></td>';
                        ?>

                        <td colspan="2"></td>
                        <td class="bottomtwo">Количество транспорта</td>
                        <td id="kolTrans">---</td>
                        <td class="bottomtwo">Проезд Итого</td>
                        <td id="itogoProezd">---</td>
                    </tr>
                    <tr>
                        <td class="bottomtwo" style="background-color: red;">Кол-во дней</td>
                        <td id="kolDays">---</td>
                        <td colspan="2"></td>
                        <td class="bottomtwo">Стоимость жилья в месяц</td>
                        <?php
                        if ($countDataAll > 0) {
                            echo '<td><input type="number" min="0" step="0.01" value="' . $cost_per_monthNew . '" name="cost_per_month" form="form" id="houseMonth" onchange="Calc()"></td>';
                        } else {
                            echo '<td><input type="number" min="0" step="0.01" value="0" name="cost_per_month" form="form" id="houseMonth" onchange="Calc()"></td>';
                        }
                        ?>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Риск на срок в %</td>
                        <?php
                        if ($countDataAll > 0) {
                            echo '<td><input type="number" min="0" step="1" name="risk_period" id="riskPer" form="form" value="' . $risk_periodNew . '" onchange="Calc()"></td>';
                        } else {
                            echo '<td><input type="number" min="0" step="1" name="risk_period" id="riskPer" form="form" value="0" onchange="Calc()"></td>';
                        }
                        ?>
                        <td colspan="2"></td>
                        <td class="bottomtwo">Количество жилья</td>
                        <td id="kolhouse">---</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Итого дней с учетом риска</td>
                        <td id="itogoDaysRisk">---</td>
                        <td colspan="2"></td>
                        <td class="bottomtwo">Итого помесячно</td>
                        <td id="totalMonth">---</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Количество выходных дней в неделю</td>
                        <?php
                        if ($countDataAll > 0) {
                            echo '<td><input type="number" min="0" step="1" value="' . $weekendsNew . '" name="weekends" id="vakWeek" form="form" onchange="Calc()" value="0"></td>';
                        } else {
                            echo '<td><input type="number" min="0" step="1" value="0" name="weekends" id="vakWeek" form="form" onchange="Calc()" value="0"></td>';
                        }
                        ?>
                        <td colspan="2"></td>
                        <td class="bottomtwo">Стоимость жилья в день</td>
                        <?php
                        if ($countDataAll > 0) {
                            echo '<td><input type="number" min="0" step="0.01" value="' . $cost_per_dayNew . '" name="cost_per_day" id="houseDay" form="form" onchange="Calc()"></td>';
                        } else {
                            echo '<td><input type="number" min="0" step="0.01" value="0" name="cost_per_day" id="houseDay" form="form" onchange="Calc()"></td>';
                        }
                        ?>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Количество выходных</td>
                        <td id="kolVak">---</td>
                        <td colspan="2"></td>
                        <td class="bottomtwo">Итого посуточно</td>
                        <td id="totalDay">---</td>
                        <td class="bottomtwo">Выгоднее</td>
                        <td id="profit" style="background-color: yellow;">---</td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Количество рабочих дней</td>
                        <td id="workDays">---</td>
                        <td colspan="2"></td>
                        <td class="bottomtwo">Проживание на человека/сутки</td>
                        <td id="placementDay">---</td>
                        <td class="bottomtwo">Итого проживание</td>
                        <td id="totalPlacement">---</td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Количество рабочих</td>
                        <?php
                        if ($countDataAll > 0) {
                            echo '<td><input type="number" min="1" step="1" name="number_workers" id="kolWorkers" form="form" value="' . $number_workersNew . '" onchange="Calc()"></td>';
                        } else {
                            echo '<td><input type="number" min="1" step="1" name="number_workers" id="kolWorkers" form="form" value="2" onchange="Calc()"></td>';
                        }
                        ?>
                        <td colspan="2"></td>
                        <td class="bottomtwo">Суточные на человека</td>
                        <?php
                        if ($countDataAll > 0) {
                            echo '<td><input type="number" min="0" step="0.01" value="' . $daily_personNew . '" name="daily_person" id="dayPeop" form="form" onchange="Calc()"></td>';
                        } else {
                            echo '<td><input type="number" min="0" step="0.01" value="0" name="daily_person" id="dayPeop" form="form" onchange="Calc()"></td>';
                        }
                        ?>
                        <td class="bottomtwo">Итого суточные</td>
                        <td id="totalDayPeop">-----------</td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Количество мес(авто)</td>
                        <td id="kolMonth">Количество месяцев</td>
                        <td colspan="2"></td>
                        <td class="bottomtwo">Расход топлива л/км</td>
                        <td id="rashodNa100">---</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Расстояние до обьекта км</td>
                        <?php
                        if ($countDataAll > 0) {
                            echo '<td><input type="number" min="0" value="' . $distance_ObjectNew . '" name="distance_Object" id="wayKM" form="form" onchange="Calc()"></td>';
                        } else {
                            echo '<td><input type="number" min="0" value="0" name="distance_Object" id="wayKM" form="form" onchange="Calc()"></td>';
                        }
                        ?>
                        <td class="bottomtwo">В обе стороны</td>
                        <td id="wayKM2">---</td>
                        <td class="bottomtwo">Итого командировачные</td>
                        <td colspan="3" id="totalKomand" style="background-color: yellow;">---</td>
                    </tr>
                    <tr>
                        <td class="bottomtwo">Цена бензин за литр</td>
                        <?php
                        if ($countDataAll > 0) {
                            echo '<td><input type="number" min="0" step="0.01" name="price_gasoline" id="benzin" form="form" value="' . $price_gasolineNew . '" onchange="Calc()"></td>';
                        } else {
                            echo '<td><input type="number" min="0" step="0.01" name="price_gasoline" id="benzin" form="form" value="47.00" onchange="Calc()"></td>';
                        }
                        ?>
                        <td class="bottomtwo">Расход л/100</td>
                        <?php
                        if ($countDataAll > 0) {
                            echo '<td><input type="number" min="0" step="0.01" name="expense" id="rashod100" form="form" value="' . $expenseNew . '" onchange="Calc()"></td>';
                        } else {
                            echo '<td><input type="number" min="0" step="0.01" name="expense" id="rashod100" form="form" value="9" onchange="Calc()"></td>';
                        }
                        ?>
                        <td class="bottomtwo">Итого затраты на рабочих</td>
                        <td colspan="3">---</td>
                    </tr>
                    <tr>

                    </tr>
                </table>

            </div>
            <script src="js/jquery-3.6.0.min.js"></script>
            <script src="js/menu.js"></script>
            <script src="js/toast.min.js"></script>
            <script src="js/Calculation.js"></script>
            <?php
            foreach ($errors as $err) {
                echo '<script> new Toast({ 
                    title: "Ошибка",
                    text: "' . $err . '",
                    theme: "danger",
                    autohide: false,
                    interval: 10000,
                  });</script>';
            }
            foreach ($success as $suc) {
                echo '<script> new Toast({ 
                    title: "Успешно",
                    text: "' . $suc . '",
                    theme: "success",
                    autohide: false,
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