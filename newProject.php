<?php
session_start();
function tologin()
{
    header("Location: index.php");
    exit();
}

function switcher_en($value)
{
    $converter = array(
        'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',    'е' => 'e',    'ё' => 'e',
        'ж' => 'zh',    'з' => 'z',    'и' => 'i',    'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',
        'н' => 'n',    'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 'c',    'т' => 't',    'у' => 'y',
        'ф' => 'f',    'х' => 'kh',    'ц' => 'ts',    'ч' => 'ch',    'ш' => 'sh',    'щ' => 'shch',    'ь' => '`',
        'ы' => 'y',    'ъ' => '`]`',    'э' => 'e',    'ю' => 'yu',    'я' => 'ya',

        'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',    'Е' => 'E',    'Ё' => 'E',
        'Ж' => 'ZH',    'З' => 'Z',    'И' => 'I',    'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',
        'Н' => 'N',    'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'C',    'Т' => 'T',    'У' => 'Y',
        'Ф' => 'F',    'Х' => 'KH',    'Ц' => 'TS',    'Ч' => 'CH',    'Ш' => 'SH',    'Щ' => 'SHCH',    'Ь' => '`',
        'Ы' => 'Y',    'Ъ' => '`',    'Э' => 'E',    'Ю' => 'YU',    'Я' => 'YA',

        ' ' => '_',
    );

    $value = strtr($value, $converter);
    return $value;
}

if (!empty($_SESSION['auth']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager')) {
    include_once "link.php";
    $projectName = $_GET['Name'];
    $err = array();
    $success = array();

    if ($projectName != null) {
        $queryProj = "SELECT * FROM `Subtype` WHERE `id` = '$projectName'";
        $resultProj = mysqli_query($link, $queryProj);
        while ($rowp = mysqli_fetch_array($resultProj)) {
            $id = $rowp['id'];
            $Name = $rowp['Name'];
            $Org = $rowp['org'];
            $counterparty = $rowp['counterparty'];
            $Manager = $rowp['Meneger'];
            $Engineer = $rowp['Engineer'];
            $contract_number = $rowp['contract_number'];
            $purpose = $rowp['purpose'];
            $Start_Date = $rowp['Start_Date'];
            $Money_Date = $rowp['Money_Date'];
            $Act_Date = $rowp['Act_Date'];
            $End_Date = $rowp['End_Date'];
        }
    }

    if (isset($_POST['buttSave'])) {
        $cust = $_POST['selectCust'];
        $naznP = $_POST['selectNazn'];
        $managerTosql = $_POST['selectMan'];
        $contract = $_POST['Contract'];

        $EngineerPost = $_POST['selectEngin'];
        $Start_DatePost = $_POST['Start_Date'];
        $Money_DatePost = $_POST['Money_Date'];
        $Act_DatePost = $_POST['Act_Date'];
        $End_DatePost = $_POST['End_Date'];

        if (isset($_POST['projectName'])) {
            $project_Name_New = $_POST['projectName'];
            $projectName = $project_Name_New;
        }

        $queryNazn = "SELECT * FROM `Subject_С`";
        $resultNazn = mysqli_query($link, $queryNazn);
        while ($rowNaz = mysqli_fetch_array($resultNazn)) {
            if ($rowNaz['abbreviation'] == $naznP) {
                $NaznWorks = $rowNaz['Name'];
            }
        }

        $queryManSQL = "SELECT * FROM `Managers`";
        $resultManSQL = mysqli_query($link, $queryManSQL);
        while ($rowMan = mysqli_fetch_array($resultManSQL)) {
            if ($rowMan['abbreviation'] == $managerTosql) {
                $ManagerFromSQL = $rowMan['Name'];
            }
        }

        if (isset($_POST['projectName'])) {
            // $queryAddToSubtypeAll = "INSERT INTO `Subtype` (`type`,`counterparty`,`contract_number`,`purpose`,`Meneger`,`Engineer`,`Start_Date`,`Money_Date`,`Act_Date`,`End_Date`) VALUES ('capex','$cust','$contract','$NaznWorks','$ManagerFromSQL','$EngineerPost','$Start_Date','$Money_Date','$Act_Date','$End_Date') WHERE `id`='$id'";
            $query_update_sql = "UPDATE `Subtype` SET `counterparty`= '$cust',`contract_number`='$contract',`purpose`='$NaznWorks',`Meneger`='$ManagerFromSQL',`Engineer`='$EngineerPost',`Start_Date`='$Start_DatePost',`Money_Date`='$Money_DatePost',`Act_Date`='$Act_DatePost',`End_Date`='$End_DatePost' WHERE `SubType` = '$project_Name_New'";
            if (mysqli_query($link, $query_update_sql)) {
                $success[] = "Данные успешно обновлены";
            } else {
                $err[] = "Проект не был обновлен из за ошибки " . mysqli_error($link);
            }
        } else {
            $new_subtype = $cust . "_" . $NaznWorks . "_" . $contract;
            $new_subtypeEN = switcher_en($new_subtype);

            $queryCheck = "SELECT * FROM `Subtype`";
            $resultCheck = mysqli_query($link, $queryCheck);
            while ($rowCh = mysqli_fetch_array($resultCheck)) {
                if ($rowCh['SubType'] == $new_subtypeEN && $rowCh['Name'] == $new_subtype && $rowCh['purpose'] == $NaznWorks) {
                    $err[] = "Данный проект уже существует";
                    $exists = true;
                } else {
                    $exists = false;
                }
            }
            if ($exists == false) {
                $queryAddToSubtype = "INSERT INTO `Subtype` (`SubType`, `Name`, `type`, `counterparty`, `contract_number`, `purpose`, `Meneger`) VALUES ('$new_subtypeEN', '$new_subtype', 'capex','$cust','$contract','$NaznWorks','$ManagerFromSQL')";
                if (mysqli_query($link, $queryAddToSubtype)) {
                    $success[] = "Проект успешно добавлен";
                } else {
                    $err[] = "Проект не был добавлен из за ошибки " . mysqli_error($link);
                }
            }
        }
    }

?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Project</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/newProject.css" rel="stylesheet" />
        <link href="css/nav.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/toast.min.css" rel="stylesheet">
    </head>

    <body onload="NumberDocument()">
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <?php
            if ($projectName == null) {
                echo "<h1>Создание нового проекта</h1>";
                echo "<h1>Название проекта: $new_subtype</h1>";
                if ($exists == true) {
                    foreach ($err as $error) {
                        echo "<h1 style='color=red;'>$error</h1>";
                    }
                }
            } else {
                echo "<h1>Редактирование проекта</h1>";
                echo "<h1>$Name</h1>";
                echo "<h1 hidden><input type='text' name='projectName' form='addProj' value='$projectName'></h1>";
            }
            ?>
            <div style="margin-top: 10%;">
                <button class="to_new" onclick="window.location.href = 'tableCheck.php'">Вернутся в таблицу "Check"</button>
                <button class="to_new" onclick="window.location.href = 'listContract.php'">Вернутся в таблицу "Список договоров"</button>
                <form method="post" id="addProj">
                    <table class="tableCost" style="width: 100%;">
                        <tr>
                            <th>№</th>
                            <th>Контрагент</th>
                            <th>Назначение проекта</th>
                            <th>Менеджер</th>
                            <th>Организация</th>
                            <th>Номер договора</th>
                            <?php
                            if ($projectName != null) {
                                echo "<th>Инжинер</th>";
                                echo "<th>Дата начала</th>";
                                echo "<th>Дата выплат</th>";
                                echo "<th>Дата подписания акта</th>";
                                echo "<th>Дата окончания</th>";
                            }
                            ?>
                        </tr>
                        <tr>
                            <?php
                            $queryNumber = "SELECT id FROM Subtype ORDER BY id DESC LIMIT 1";
                            $resultNumber = mysqli_query($link, $queryNumber);
                            while ($number = mysqli_fetch_array($resultNumber)) {
                                if ($projectName == null) {
                                    echo "<td><input type='text' name= 'id' id= 'idSub' form= 'addProj' value = '" . $numberEcho = $number['id'] + 1 . "' disabled></td>";
                                } else {
                                    echo "<td><input type='text' name= 'id' id= 'idSub' form= 'addProj' value = '" . $id . "' disabled></td>";
                                }
                            }

                            $queryCust = "SELECT * FROM `Customers`";
                            $resultCust = mysqli_query($link, $queryCust);
                            echo "<td>";
                            echo "<select id='selectCust' class='selectCust' name='selectCust' form='addProj' onchange='NumberDocument()'>";
                            echo "<option value=''>Не выбрано</option>";
                            while ($objCust = mysqli_fetch_array($resultCust)) {
                                if ($objCust['Name'] == $counterparty) {
                                    echo "<option selected value='" . $objCust['Name'] . "'> " . $objCust['Name'] . " </option>";
                                } else {
                                    echo "<option value='" . $objCust['Name'] . "'> " . $objCust['Name'] . " </option>";
                                }
                            }
                            echo "</select>";
                            echo "</td>";

                            $querySelect = "SELECT * FROM `Subject_С`";
                            $resultSelect = mysqli_query($link, $querySelect);
                            echo "<td>";
                            echo "<select id='selectNazn' class='selectCust' name='selectNazn' form='addProj' onchange='NumberDocument()'>";
                            echo "<option value='??'>Не выбрано</option>";
                            while ($objectOrg = mysqli_fetch_array($resultSelect)) {
                                if ($objectOrg['Name'] == $purpose) {
                                    echo "<option selected value='" . $objectOrg['abbreviation'] . "'> " . $objectOrg['Name'] . " </option>";
                                } else {
                                    echo "<option value='" . $objectOrg['abbreviation'] . "'> " . $objectOrg['Name'] . " </option>";
                                }
                            }
                            echo "</select>";
                            echo "</td>";

                            $queryManager = "SELECT * FROM `Managers`";
                            $resultManager = mysqli_query($link, $queryManager);
                            echo "<td>";
                            echo "<select id='selectMan' class='selectCust' name='selectMan' form='addProj' onchange='NumberDocument()'>";
                            echo "<option value='??'>Не выбрано</option>";
                            while ($objManager = mysqli_fetch_array($resultManager)) {
                                if ($objManager['Name'] == $Manager) {
                                    echo "<option selected value='" . $objManager['abbreviation'] . "' > " . $objManager['Name'] . " </option>";
                                } else {
                                    echo "<option value='" . $objManager['abbreviation'] . "'> " . $objManager['Name'] . " </option>";
                                }
                            }
                            echo "</select>";
                            echo "</td>";

                            $queryOrg = "SELECT * FROM org";
                            $resultOrg = mysqli_query($link, $queryOrg);
                            echo "<td>";
                            echo "<select id='selectOrg' class='selectCust' name='selectOrg' form='addProj' onchange='NumberDocument()'>";
                            echo "<option value='0'>Не выбрано</option>";
                            while ($rowOrg = mysqli_fetch_array($resultOrg)) {
                                if ($rowOrg['org'] == $Org) {
                                    echo "<option selected value='" . $rowOrg['org'] . "' > " . $rowOrg['org'] . " </option>";
                                } else {
                                    echo "<option value='" . $rowOrg['org'] . "'> " . $rowOrg['org'] . " </option>";
                                }
                            }
                            echo "</select>";
                            echo "</td>";


                            ?>

                            <td><input type="text" name="Contract" id="Contract" form='addProj' value="??/??/??"></td>
                            <?php
                            if ($projectName != null) {
                                $queryEngin = "SELECT * FROM `Engineers`";
                                $resultEngin = mysqli_query($link, $queryEngin);
                                echo "<td>";
                                echo "<select id='selectEngin' class='selectCust' name='selectEngin' form='addProj' onchange='NumberDocument()'>";
                                echo "<option value=''>Не выбрано</option>";
                                while ($rowEngin = mysqli_fetch_array($resultEngin)) {
                                    if ($rowEngin['Name'] == $Engineer) {
                                        echo "<option selected value='" . $rowEngin['Name'] . "' > " . $rowEngin['Name'] . " </option>";
                                    } else {
                                        echo "<option value='" . $rowEngin['Name'] . "'> " . $rowEngin['Name'] . " </option>";
                                    }
                                }
                                echo "<td><input type='date' name='Start_Date' id='Start_Date' class='inputP' form='addProj' value='$Start_Date'></td>";
                                echo "<td><input type='date' name='Money_Date' id='Money_Date' class='inputP' form='addProj' value='$Money_Date'></td>";
                                echo "<td><input type='date' name='Act_Date' id='Act_Date' class='inputP' form='addProj' value='$Act_Date'></td>";
                                echo "<td><input type='date' name='End_Date' id='End_Date' class='inputP' form='addProj' value='$End_Date'></td>";
                            } ?>

                        </tr>
                    </table>
                    <br>
                    <input type="submit" id="buttSave" form='addProj' name="buttSave" value="Сохранить">
                </form>
            </div>
        </div>
        <script src="js/jquery-3.6.0.min.js"></script>
        <script src="js/jquery.sumoselect.js"></script>
        <script src="js/filter.js"></script>
        <script src="js/menu.js"></script>
        <script src="js/newProj.js"></script>
        <script src="js/toast.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.selectCust').SumoSelect({
                    search: true,
                });
            })
        </script>
        <?php
        foreach ($err as $error) {
            echo '<script> new Toast({ 
                title: "Ошибка",
                text: "' . $error . '",
                theme: "error",
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