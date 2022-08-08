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
    return $data;
}

if (!empty($_SESSION['auth']) && ($_SESSION['role'] == 'admin')) {
    $errors = array();
    include_once "link.php";

    $get_id = (int)$_GET['id'];

    if (isset($_POST['saveCost']) && isset($_GET['id'])) {

        for ($i = 0; $i < count($_POST['add_date']); $i++) {
            $date_list[] = check_post($_POST['add_date'][$i]);
            $name_list[] = check_post($_POST['add_name'][$i]);
            $summ_list[] = check_post($_POST['add_summ'][$i]);
            $selectType_list[] = check_post($_POST['selectType'][$i]);
            $selectSubType_list[] = check_post($_POST['selectSubType'][$i]);
            $selectName_list[] = check_post($_POST['selectName'][$i]);
            $selectOrg_list[] = check_post($_POST['selectOrg'][$i]);
        }

        $number_of_dupl = 0;
        $number_of_errors = 0;
        for ($i = 0; $i < count($_POST['add_date']); $i++) {
            $query_check_diplicates = "SELECT * FROM `Cost` WHERE `Дата`='$date_list[$i]' AND `Наименование`='$name_list[$i]' AND `Сумма`='$summ_list[$i]' AND `type`='$selectType_list[$i]' AND `Subtype`='$selectSubType_list[$i]' AND `name`='$selectName_list[$i]' AND `org`='$selectOrg_list[$i]'";
            $result = mysqli_query($link, $query_check_diplicates);
            $count_dupl = mysqli_num_rows($result);
            if ($count_dupl > 0) {
                $number_of_dupl++;
                $errors[] = "Найден дубликат " . $name_list[$i] . ". Изменение прервано.";
            }
        }
        if ($number_of_dupl == 0) {
            $number_of_errors++;
            if (mysqli_query($link, "DELETE FROM `Cost` WHERE `id` = '$get_id';")) {
                for ($i = 0; $i < count($_POST['add_date']); $i++) {
                    $insert_cost = "INSERT INTO `Cost`(`Дата`, `Наименование`, `Сумма`, `type`, `Subtype`, `name`, `org`) VALUES ('$date_list[$i]','$name_list[$i]','$summ_list[$i]','$selectType_list[$i]','$selectSubType_list[$i]','$selectName_list[$i]','$selectOrg_list[$i]')";
                    if (mysqli_query($link, $insert_cost)) {
                        $success[] = "Done";
                    } else {
                        $number_of_errors++;
                        $errors[] = "Произошла ошибка при добавлении: " . mysqli_error($link);
                    }
                }
            } else {
                $errors[] = "Произошла ошибка при удалении: " . mysqli_error($link);
            }
        }

        if ($number_of_errors == 1) {
            header("Location: tableCost.php");
            exit();
        }
        // $query_to_Cost = "UPDATE `Cost` SET `type`='$type_up',`Subtype`='$Subtype_up',`name`='$name_up',`org`='$org_up' WHERE `id` = '$get_id';";
        // if (mysqli_query($link, $query_to_Cost)) {
        //     header("Location: tableCost.php");
        //     exit();
        // } else {
        //     $error = "Не изменено из-за ошибки " . mysqli_error($link);
        // }
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Редактирование</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />
        <link href="css/nav.css" rel="stylesheet" />
        <link href="css/toast.min.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/EditCost.css" rel="stylesheet" />


    </head>

    <body>
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <?php
            $result_subtype = mysqli_query($link, "SELECT * FROM Cost WHERE `id` = '$get_id'");
            while ($rowSub = mysqli_fetch_array($result_subtype)) {
                $date = $rowSub['Дата'];
                $full_name = $rowSub['Наименование'];
                $summ = $rowSub['Сумма'];
                $type = $rowSub['type'];
                $Subtype = $rowSub['Subtype'];
                $name = $rowSub['name'];
                $org = $rowSub['org'];
            }

            echo "<h1>Разделение позиции:</h1>";
            echo "<h1>$full_name</h1>";
            ?>
            <div style="margin:4% 0 0 0;" onload="add_row()">
                <table class="tableCost" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Дата</th>
                            <th>Наименование</th>
                            <th>Сумма</th>
                            <th>Тип</th>
                            <th>Подтип</th>
                            <th>Имя</th>
                            <th>Организация</th>
                        </tr>
                        <tr>
                            <th><?php echo $get_id; ?></th>
                            <th><?php echo $date; ?></th>
                            <th><?php echo $full_name; ?></th>
                            <th id="full_summ"><?php echo $summ; ?></th>
                            <th><?php echo $type; ?></th>
                            <th><?php echo $Subtype; ?></th>
                            <th><?php echo $name; ?></th>
                            <th><?php echo $org; ?></th>
                        </tr>
                        <tr>
                            <th colspan="8" style="font-size: medium;">Добавьте строки которые заменят данную запись (минимум 2). Сумма строк должна совпадать с исходной.</th>
                        </tr>
                        <form method="post" id="EditCost">
                    </thead>
                    <tbody id="tbody">
                        <tr>
                            <?php
                            $result_id = mysqli_query($link, "SELECT `id` FROM `Cost` ORDER BY `id` DESC LIMIT 1;");
                            $result_id_new = mysqli_fetch_array($result_id);
                            $last_id = $result_id_new['id'];
                            $last_id++;
                            echo "<th> $last_id </th>";
                            ?>
                            <!-- <th>Номер(автомат)</th> -->
                            <th><input type="date" name="add_date[]" required></th>
                            <th><input type="text" name="add_name[]" required></th>
                            <th><input type="number" name="add_summ[]" class="summ_check" min="0" step="0.01" onchange="check_summ()" required></th>
                            <th>
                                <?php


                                $querytype = "SELECT * FROM `type`";
                                $resulttype = mysqli_query($link, $querytype);
                                echo "<select id='selectType' class='select'  name='selectType[]' form='EditCost' required>";
                                echo  "<option value=''>Выбор</option>";
                                while ($object = mysqli_fetch_array($resulttype)) {

                                    echo "<option value = '" . $object['type'] . "' > " . $object['type'] . " </option>";
                                }
                                echo "</select>";
                                ?>
                            </th>
                            <th>
                                <?php
                                $result_to_subtype = mysqli_query($link, "SELECT `SubType` FROM `Subtype`");
                                echo "<select id='selectSubType' class='select' name='selectSubType[]' form='EditCost' required>";
                                echo  "<option value=''>Выбор</option>";
                                while ($obj1 = mysqli_fetch_array($result_to_subtype)) {

                                    echo "<option value = " . $obj1['SubType'] . " > " . $obj1['SubType'] . " </option>";
                                }
                                echo "</select>";
                                ?>
                            </th>
                            <th>
                                <?php
                                $result_to_name = mysqli_query($link, "SELECT `name` FROM `name`");
                                echo "<select id='selectName' class='select' name='selectName[]' form='EditCost' required>";
                                echo  "<option value=''>Выбор</option>";
                                while ($objName = mysqli_fetch_array($result_to_name)) {

                                    echo "<option value = " . $objName['name'] . " > " . $objName['name'] . " </option>";
                                }
                                echo "</select>";
                                ?>
                            </th>
                            <th>
                                <?php
                                $result_org = mysqli_query($link, "SELECT `org` FROM `org`");
                                echo "<select id='selectOrg' class='select' name='selectOrg[]' form='EditCost' required>";
                                echo  "<option value=''>Выбор</option>";
                                while ($objName = mysqli_fetch_array($result_org)) {

                                    echo "<option value = " . $objName['org'] . " > " . $objName['org'] . " </option>";
                                }
                                echo "</select>";
                                ?>
                            </th>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" class="to_new" name="saveCost" value="Сохранить" form='EditCost'>
                </form>
                <button onclick="add_row()" class="to_new" style="margin-left: 50px;">Добавить строку</button>
            </div>
        </div>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/jquery.sumoselect.js"></script>
        <script src="../js/filter.js"></script>
        <script src="../js/toast.min.js"></script>
        <script src="../js/add_row_edit_cost.js"></script>
        <script src="../js/menu.js"></script>
        <script>
            window.onload = function() {
                add_row();
            }
            $(document).ready(function() {
                $('.select').SumoSelect({
                    search: true,
                });
            })
        </script>
        <?php
        if (isset($_POST['saveCost'])) {
            foreach ($errors as $error) {
                echo '<script> new Toast({ 
                title: "Ошибка",
                text: "' . $error . '",
                theme: "warning",
                autohide: true,
                interval: 10000,
              });</script>';
            }
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