<?php
require 'C:\OpenServer\vendor\autoload.php';
// require '/var/www/s182635/data/bin/vendor/autoload.php';

use \PhpOffice\PhpSpreadsheet\Shared\Date;

$arrayExel = array(array(), array());
$file = './files/CostData.xlsx'; // файл для получения данных
$excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);; // подключить Excel-файл
$excel->setActiveSheetIndex(0); // получить данные из указанного листа

$sheet = $excel->getActiveSheet();
$oCells = $sheet->getCellCollection();

for ($iRow = 1; $iRow <= $oCells->getHighestRow(); $iRow++) {
    //for ($iCol = 'A'; $iCol <= 'C'; $iCol++)
    for ($iCol = 'A'; $iCol <= $oCells->getHighestColumn(); $iCol++) {
        $oCell = $oCells->get($iCol . $iRow);
        if ($oCell && $iCol == 'A') {
            $arrayExel[$iRow][$iCol] = $oCell->getValue();
        }
        if ($oCell && $iCol != 'A') {
            $value = $oCell->getValue();
            $arrayExel[$iRow][$iCol] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
        }
    }
}

include_once "link.php";


AddStatus($link, $arrayExel);


function AddStatus($link, $arrayExel)
{
    $result = mysqli_query($link, "SELECT `SubType` FROM `Subtype`;");
    while ($row = mysqli_fetch_array($result)) {
        $SubArray[] = $row['SubType'];
    }
    echo "<table>";
    for ($i = 0; $i < count($arrayExel); $i++) {
        echo "<tr>";
        if (in_array($arrayExel[$i]['A'], $SubArray)) {
            $nameSub = $arrayExel[$i]['A'];
            echo "<td style='background: green;'>" . $arrayExel[$i]['A'] . "</td>";
            if ($arrayExel[$i]['B'] != "") {
                echo "<td style='background: green;'>" . date("Y-m-d", $arrayExel[$i]['B']) . "</td>";
                $dateS = date("Y-m-d", $arrayExel[$i]['B']);
                if (mysqli_query($link, "UPDATE `Subtype` SET `Start_Date` = '$dateS' WHERE `SubType` = '$nameSub';")) {
                    $mess = "1-Успешно";
                } else {
                    $mess = "1: " . mysqli_error($link);
                }
            } else {
                echo "<td style='background: yellow;'>пусто</td>";
            }
            if ($arrayExel[$i]['C'] != "") {
                echo "<td style='background: green;'>" . date("Y-m-d", $arrayExel[$i]['C']) . "</td>";
                $dateM = date("Y-m-d", $arrayExel[$i]['C']);
                if (mysqli_query($link, "UPDATE `Subtype` SET `Money_Date` = '$dateM' WHERE `SubType` = '$nameSub';")) {
                    $mess .= " 2-Успешно";
                } else {
                    $mess .= " 2: " . mysqli_error($link);
                }
            } else {
                echo "<td style='background: yellow;'>пусто</td>";
            }
            if ($arrayExel[$i]['D'] != "") {
                echo "<td style='background: green;'>" . date("Y-m-d", $arrayExel[$i]['D']) . "</td>";
                $dateEnd = date("Y-m-d", $arrayExel[$i]['D']);
                if (mysqli_query($link, "UPDATE `Subtype` SET `Act_Date` = '$dateEnd' WHERE `SubType` = '$nameSub';")) {
                    $mess = " 3-Успешно";
                } else {
                    $mess = " 3: " . mysqli_error($link);
                }
            } else {
                echo "<td style='background: yellow;'>пусто</td>";
            }
            echo "<td>$mess</td>";
        } else {
            echo "<td style='background: red;'>" . $arrayExel[$i]['A'] . "</td>";
        }

        echo "</tr>";
    }
    echo "</table>";
}


exit;
