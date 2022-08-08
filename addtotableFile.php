<?php
session_start();
function tologin()
{
    header("Location: index.php");
    exit();
}

require 'C:\OpenServer\vendor\autoload.php';


// require '/var/www/s182635/data/bin/vendor/autoload.php';

use \PhpOffice\PhpSpreadsheet\Shared\Date;

function get_file()
{

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
            if ($oCell) {
                $arrayExel[$iRow][$iCol] = $oCell->getValue();
            }

            // if ($oCell && $iCol != 'A') {
            //     $value = $oCell->getValue();
            //     $arrayExel[$iRow][$iCol] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
            // }
        }
    }
    return $arrayExel;
}



// AddStatus($link, $arrayExel);


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




?>
<!DOCTYPE html>
<html>

<head>
    <title>Загрузка из файла</title>
    <meta charset="utf-8" />
    <link href="css/sumoselect.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
    <link href="css/nav.css" rel="stylesheet" />
    <style type="text/css">
    .button {
        margin-top: 30px;
        font-weight: 700;
        color: white;
        text-decoration: none;
        padding: .8em 1em calc(.8em + 3px);
        background: rgb(64, 199, 129);
        box-shadow: 0 -3px rgb(53, 167, 110) inset;
        transition: 0.2s;
    }

    .button:hover {
        background: rgb(53, 167, 110);
    }

    .button:active {
        background: rgb(33, 147, 90);
        box-shadow: 0 3px rgb(33, 147, 90) inset;
    }

    </style>
</head>

<body>
    <?php include_once "sidenav.php"; ?>
    <div class="main">

        <h1>Выберите файл Exel</h1>
        <?php
        if (isset($_POST['submit_upload'])) {
            echo "<h1>Загружено</h1>";
            echo "<br>";
            echo var_dump($_SESSION['file_array']);
        }


        if (isset($_SESSION['message']) && $_SESSION['message']) {
            printf('<b>%s</b>', $_SESSION['message']);
            unset($_SESSION['message']);
        }
        ?>
        <form method="POST" action="upload.php" enctype="multipart/form-data">
            <div>
                <span style="font-size:large; font-weight:200;">Загрузите файл:</span>
                <input class="button" type="file" name="uploadedFile" />
            </div>

            <input class="button" type="submit" name="uploadBtn" value="Загрузить" />
        </form>
        <?php
        if (!empty($_SESSION['auth']) && ($_SESSION['role'] == 'admin')) {

            include_once "link.php";

            if (isset($_SESSION['success_upload']) && $_SESSION['success_upload']) {
                echo "<br>";
                echo "<h2 style='color:blue;'>Предварительный вид данных</h2>";
                echo "<h3>Если данные совпадают, то нажмите на кнопку [Добавить данные]</h3>";

                echo "<form method='POST'>";
                // echo "<input type='hidden' value='$file_array'>";

                echo "<input type='submit' name='submit_upload' value='Добавить данные' class='button'>";
                echo "</form>";
                echo "<br>";

                $file_array = get_file();
                $_SESSION['file_array'] = $file_array;
                echo "<table class='tableCost' style='width:100%'>";
                foreach ($file_array as $row_array) {
                    echo "<tr>";
                    foreach ($row_array as $arr) {
                        echo "<td>$arr</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";

                // header("Location: read_exel.php");
                unset($_SESSION['success_upload']);
            }
        ?>
</body>

</html>
<?php
            mysqli_close($link);
        } else {
            tologin();
        }
?>
