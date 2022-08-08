<?php
session_start();

function check_post($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = addslashes($data);
    return $data;
}

$message = '';
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Загрузить') {
    $_SESSION['success_upload'] = false;
    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
        // get details of the uploaded file
        $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
        $fileName = $_FILES['uploadedFile']['name'];
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // sanitize file-name
        $newFileName = "Calculation_to_sql" . '.' . $fileExtension;

        // check if file has one of the following extensions
        $allowedfileExtensions = array('xls', 'xlsx');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // directory in which the uploaded file will be moved
            $uploadFileDir = './files/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $message = 'Файл успешно загружен.';
                $_SESSION['success_upload'] = true;
            } else {
                $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            }
        } else {
            $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    } else {
        $message = 'There is some error in the file upload. Please check the following error.<br>';
        $message .= 'Error:' . $_FILES['uploadedFile']['error'];
    }
}
$_SESSION['message'] = $message;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SESSION['success_upload'] == false) {
    header("Location: tableCalculation.php");
} else {
    require 'C:\OpenServer\vendor\autoload.php';
    // require '/var/www/s182635/data/bin/vendor/autoload.php';

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('./files/Calculation_to_sql.xlsx');

    $sheet = $spreadsheet->getActiveSheet();


    // $arrayExel = array(array(), array());
    // $file = './files/CostData.xlsx'; // файл для получения данных
    // $excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);; // подключить Excel-файл
    // $excel->setActiveSheetIndex(0); // получить данные из указанного листа

    // $sheet = $excel->getActiveSheet();
    $oCells = $sheet->getCellCollection();
    $check_string = "Пуско-наладочные работы";
    $number_workers = 0;
    for ($row = 1; $row <= $oCells->getHighestRow(); $row++) {
        $oCell = $oCells->get('A' . $row);
        if ($oCell) {
            $cell = $oCell->getValue();
            if ($check_string == $cell) {
                $stop_row = (int)$row;
            }
            if ($cell == "Монтажник" || $cell == "Бригадир") {
                $number_workers++;
            }
        }
    }

    for ($col = 'A'; $col <= 'X'; $col++) {
        $oCell = $oCells->get($col . 1);
        if ($oCell) {
            $cell = $oCell->getValue();
            if ($cell == "Ед.изм.") {
                $units_col = $col;
            }
            if ($cell == "Количество") {
                $quantity_col = $col;
            }
            if ($cell == "замена") {
                $replacement_col = $col;
            }
            if ($cell == "Цена оборудования") {
                $percent_Equipment_col = $col;
            }
            if ($cell == "Цена работ") {
                $percentWork_col = $col;
            }
        }
        $oCell = $oCells->get($col . 2);
        if ($oCell) {
            $cell = $oCell->getValue();
            if ($cell == "замена") {
                $replacement_col = $col;
            }
            if ($cell == "тайминг") {
                $timing_col = $col;
            }
            if ($cell == "ганимед") {
                $provider1_col = $col;
            }
            if ($cell == "этм пенза") {
                $provider2_col = $col;
            }
            if ($cell == "ситилинк") {
                $provider3_col = $col;
            }
            if ($cell == "Болид") {
                $provider4_col = $col;
            }
            if ($cell == "макс") {

                $link_col = $col;
                $link_col1 = ++$link_col;
                $link_col2 = ++$link_col;
                $link_col3 = ++$link_col;
            }
        }
    }
    // echo var_dump($link_col1) . "<br>";
    // echo var_dump($link_col2) . "<br>";
    // echo var_dump($link_col3) . "<br>";
    // echo "<br> РАЗДЕЛЕНИЕ <br>";
    for ($iRow = 4; $iRow <= $stop_row - 3; $iRow++) {
        for ($iCol = 'A'; $iCol <= 'X'; $iCol++) {
            $oCell = $oCells->get($iCol . $iRow);
            if ($oCell) {
                $cell = $oCell->getValue();
                if ($iCol == 'A') {
                    $name[] = check_post($cell);
                }
                if ($iCol == $units_col) {
                    $units[] = check_post($cell);
                }
                if ($iCol == $quantity_col) {
                    $quantity[] = ($cell);
                }
                if ($iCol == $replacement_col) {
                    $replacement[] = ($cell);
                }
                if ($iCol == $timing_col) {
                    $timing[] = ($cell);;
                }
                if ($iCol == $provider1_col) {
                    $provider1[] = ($cell);
                }
                if ($iCol == $provider2_col) {
                    $provider2[] = ($cell);
                }
                if ($iCol == $provider3_col) {
                    $provider3[] = ($cell);
                }
                if ($iCol == $provider4_col) {
                    $provider4[] = ($cell);
                }
                if ($iCol == $link_col1) {
                    $link1 = check_post($cell);
                }
                if ($iCol == $link_col2) {
                    $link2 = check_post($cell);
                }
                if ($iCol == $link_col3) {
                    $link3 = check_post($cell);
                }
            }
        }
        if ($link1 != "" || $link2 != "" || $link3 != "") {
            $link_url[] = ($link1 . "  " . $link2 . "  " . $link3);
        } else {
            $link_url[] = "";
        }
        $link1 = "";
        $link2 = "";
        $link3 = "";
    }
    // foreach ($link_url as $url) {
    //     echo $url . "<br>";
    // }
    $oCell = $oCells->get($percent_Equipment_col . 2);
    $percent_Equipment = ($oCell->getValue());

    $oCell = $oCells->get($percentWork_col . 2);
    $percentWork = ($oCell->getValue());

    $timingAll = null; // ?????

    $oCell = $oCells->get('B' . ($stop_row + 4));
    if ($oCell) {
        $logistics = ($oCell->getValue());
    } else {
        $logistics = 0;
    }

    $oCell = $oCells->get('D' . ($stop_row + 44));
    //Там формула
    $business_trips = 0;

    $oCell = $oCells->get('G' . $stop_row);
    $commissioning = 0;
    //Тут тоже формула

    $oCell = $oCells->get($replacement_col . $stop_row);
    $percent10 = ($oCell->getValue());

    $oCell = $oCells->get('B' . ($stop_row + 23));
    $risk_period = ($oCell->getValue());

    $oCell = $oCells->get('B' . ($stop_row + 25));
    $weekends = ($oCell->getValue());

    $oCell = $oCells->get('B' . ($stop_row + 32));
    $distance_Object = ($oCell->getValue());

    $oCell = $oCells->get('B' . ($stop_row + 33));
    $price_gasoline = ($oCell->getValue());

    $oCell = $oCells->get('D' . ($stop_row + 33));
    $expense = ($oCell->getValue());

    $oCell = $oCells->get('B' . ($stop_row + 37));
    $cost_per_month = ($oCell->getValue());

    $oCell = $oCells->get('B' . ($stop_row + 40));
    $cost_per_day = ($oCell->getValue());

    $oCell = $oCells->get('B' . ($stop_row + 43));
    $daily_person = ($oCell->getValue());

    if (isset($_POST['hidden_id'])) {
        $id = $_POST['hidden_id'];
        $success = array();
        $errors = array();
        include_once "link.php";
        $result_delete = mysqli_query($link, "DELETE FROM `DataFromCalculation` WHERE `tag` = '$id'");
        $result_delete_all = mysqli_query($link, "DELETE FROM `DataFromCalculationAll` WHERE `tag` = '$id'");
        $num = 1;
        do {
            $duplicates = array_unique(array_diff_assoc($name, array_unique($name)));
            if (count($duplicates) > 0) {
                foreach ($duplicates as $dup => $value) {
                    if (isset($name[$dup]) && $name[$dup] != "") {
                        $name[$dup] = $value . "($num)";
                    }
                }
            } else {
                $duplicates_exists = false;
            }
            $num++;
        } while ($duplicates_exists === true);

        // echo var_dump($duplicates);
        // echo var_dump($name);
        for ($i = 0; $i <= count($name); $i++) {
            if ($name[$i] != null) {
                if ($replacement[$i] == null) $replacement[$i] = 0;
                if (is_string($timing[$i])) {
                    $timing[$i] = 0;
                    $errors[] = "Невозможно передать значение переменной Тайминг у обьекта - " . $name[$i];
                }
                if (is_string($quantity[$i])) {
                    $quantity[$i] = 0;
                    $errors[] = "Невозможно передать значение переменной Количество у обьекта - " . $name[$i];
                }
                if (is_string($replacement[$i])) {
                    $replacement[$i] = 0;
                    $errors[] = "Невозможно передать значение переменной Замена у обьекта - " . $name[$i];
                }
                if (is_string($provider1[$i])) {
                    $provider1[$i] = 0;
                    $errors[] = "Невозможно передать значение переменной Поставщик Ганимед у обьекта - " . $name[$i];
                }
                if (is_string($provider2[$i])) {
                    $provider2[$i] = 0;
                    $errors[] = "Невозможно передать значение переменной Поставщик ЭТМ Пенза у обьекта - " . $name[$i];
                }
                if (is_string($provider3[$i])) {
                    $provider3[$i] = 0;
                    $errors[] = "Невозможно передать значение переменной Поставщик Ситилинк у обьекта - " . $name[$i];
                }
                if (is_string($provider4[$i])) {
                    $provider4[$i] = 0;
                    $errors[] = "Невозможно передать значение переменной Поставщик Болид у обьекта - " . $name[$i];
                }
                if ($provider1[$i] == null) $provider1[$i] = 0;
                if ($provider2[$i] == null) $provider2[$i] = 0;
                if ($provider3[$i] == null) $provider3[$i] = 0;
                if ($provider4[$i] == null) $provider4[$i] = 0;
                if ($timing[$i] == null) $timing[$i] = 0;

                if (mysqli_query($link, "INSERT INTO `DataFromCalculation` (`tag`, `Наименование`, `units`, `quantity`, `replacement`, `timing`, `provider1`, `provider2`, `provider3`, `provider4`, `link`) VALUES ('$id', '$name[$i]', '$units[$i]', '$quantity[$i]', '$replacement[$i]', '$timing[$i]', '$provider1[$i]', '$provider2[$i]', '$provider3[$i]', '$provider4[$i]', '$link_url[$i]');")) {
                    $success[] = "Данные обьекта $name[$i] успешно добавлены";
                } else {
                    echo "Ошибка: " . mysqli_error($link);
                    $errors[] = "Возникла ошибка при добавлении обтекта $name[$i] " . mysqli_error($link);
                }
            }
        }
        if ($percent10 == null) $percent10 = 0;
        if ($risk_period == null) $risk_period = 0;
        if ($weekends == null) $weekends = 0;
        if ($number_workers == null) $number_workers = 1;
        if ($distance_Object == null) $distance_Object = 0;
        if ($price_gasoline == null) $price_gasoline = 0;
        if ($expense == null) $expense = 0;
        if ($cost_per_month == null) $cost_per_month = 0;
        if ($cost_per_day == null) $cost_per_day = 0;
        if ($daily_person == null) $daily_person = 0;
        if (mysqli_query(
            $link,
            "INSERT INTO `DataFromCalculationAll` (`tag`, `percent_Equipment`, `percentWork`, `timingAll`, `logistics`, `business_trips`, `commissioning`, `percent10`, `risk_period`, `weekends`, `number_workers`, `distance_Object`, `price_gasoline`, `expense`, `cost_per_month`, `cost_per_day`, `daily_person`) VALUES ('$id', '$percent_Equipment', '$percentWork', 228, '$logistics', '$business_trips', '$commissioning', '$percent10', '$risk_period', '$weekends', '$number_workers', '$distance_Object', '$price_gasoline', '$expense', '$cost_per_month', '$cost_per_day', '$daily_person');"
        )) {
            $success[] = "Данные нижней таблицы успешно добавлены";
        } else {
            echo "Ошибка: " . mysqli_error($link);
            $errors[] = "Возникла ошибка при добавлении нижней таблицы " . mysqli_error($link);
        }
    } else {
        echo "Нету";
    }
    // var_dump($name);
    // foreach ($errors as $err) {
    //     echo $err . "<br>";
    // }
    // foreach ($success as $succ) {
    //     echo $succ . "<br>";
    // }
    $count_succ = count($success);
    $_SESSION['upload_calc_succ'] = "Успешно добавлено $count_succ строк";
    $_SESSION['upload_calc_err'] = $errors;

    header("Location: tableCalculation.php?nameToCalc=$id");
    exit();

    // echo "<table>";
    // for ($iRow = 1; $iRow <= $oCells->getHighestRow(); $iRow++) {
    //     echo "<tr>";
    //     // $oCells->getHighestColumn()
    //     for ($iCol = 'A'; $iCol <= 'V'; $iCol++) {
    //         $oCell = $oCells->get($iCol . $iRow);
    //         echo "<td>";
    //         if ($oCell) {
    //             $arrayExel[$iRow][$iCol] = $oCell->getValue();
    //             echo $oCell->getValue();
    //         }
    //         echo "</td>";
    //     }
    //     echo "</tr>";
    // }
    // echo "</table>";
}
