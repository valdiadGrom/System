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

if (!empty($_SESSION['auth']) && ($_SESSION['role'] == 'admin')) {
    $errors = array();
    $success = array();

    include_once "link.php";
    if (isset($_POST['submit_menu'])) {
        $change_id = check_post($_POST['id']);
        $new_date = check_post($_POST['change_date']);
        $new_name = check_post($_POST['change_name']);
        $new_summ = check_post($_POST['summ']);
        $new_type = check_post($_POST['selectType']);
        $new_subtype = check_post($_POST['selectSubtype']);
        $new_name_long = check_post($_POST['selectName']);
        $new_org = check_post($_POST['selectOrg']);

        $query_check_cost = "SELECT * FROM `Cost` WHERE `id` = '$change_id';";
        $result_check = mysqli_query($link, $query_check_cost);
        $count_check = mysqli_num_rows($result_check);
        if ($count_check == 0) {
            $errors[] = "Данной позиции нет в базе";
        }

        if ($count_check == 1) {
            $query_update_cost = "UPDATE `Cost` SET `Дата`='$new_date',`Наименование`='$new_name',`Сумма`='$new_summ',`type`='$new_type',`Subtype`='$new_subtype',`name`='$new_name_long',`org`='$new_org' WHERE `id`='$change_id';";

            if (mysqli_query($link, $query_update_cost)) {
                $success[] = "Данные обновлены";
            } else {
                $errors[] = "Данные не были обновлены из-за ошибки " . mysqli_error($link);
            }
        }
    }

?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Вывод из таблиц</title>
        <meta charset="utf-8" />
        <link href="css/sumoselect.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />
        <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
        <link href="css/nav.css" rel="stylesheet" />
        <link href="css/check.css" rel="stylesheet" />
        <link href="css/toast.min.css" rel="stylesheet">

    </head>

    <body>
        <?php include_once "sidenav.php"; ?>
        <div class="main">
            <h1>COST</h1>
            <table class="tableCost" style="width: 100%;">
                <tr>
                    <th>---</th>
                    <th><a id="butt" href="addtotable.php">Ручное добавление</a></th>
                </tr>
                <tr>
                    <th>Добавление данных в таблицу</th>
                    <th><a id="butt" href="addtotableFile.php">Добавление из файла Exel</a></th>
                </tr>
                <tr>
                    <th><button onclick="window.location.href = 'createExel.php'">Скачать Cost в формате Exel</button></th>
                    <th><a id="butt">Добавление из 1С</a></th>
                </tr>
            </table>
            <br>
            <br>
            <div id="filtr">
                <button class="reset" id="reset" onclick="reset(1)">Сбросить</button>
            </div>
            <table id="Ftable" class="tableCost" style="width: 100%;">
                <thead>
                    <tr>
                        <th rowspan="2" style="display: none;">id</th>
                        <th>Дата</th>
                        <th>Наименование</th>
                        <th>Сумма</th>
                        <th>Тип</th>
                        <th>Подтип</th>
                        <th>Имя</th>
                        <th>Организация</th>
                    </tr>
                    <tr>
                        <th>
                            <!-- <?php
                                    $querytype1 = "SELECT Дата FROM Cost";
                                    $resulttype1 = mysqli_query($link, $querytype1);
                                    echo "<select id='selectData' class = 'selectNew' name = 'selectData' onchange='pip()'>";
                                    echo "<option value=''>Выбор</option>";
                                    while ($object1 = mysqli_fetch_array($resulttype1)) {
                                        echo "<option value = " . $object1['Дата'] . " > " . $object1['Дата'] . " </option>";
                                    }
                                    echo "</select>";
                                    ?> -->

                            <input type="date" id="selectData" value="<?php echo date("Y-m-d"); ?>" onchange="pip()">
                            <input id="hide_date" type="hidden" name="date_hidden" value="<?php echo date("Y-m-d"); ?>">
                        </th>
                        <th>
                            <?php
                            $querytype1 = "SELECT Наименование FROM Cost";
                            $resulttype1 = mysqli_query($link, $querytype1);
                            echo "<select id='selectnaim' class = 'selectNew' name = 'selectnaim' onchange='pip()'>";
                            echo "<option value=''>Выбор</option>";
                            while ($object1 = mysqli_fetch_array($resulttype1)) {
                                echo "<option value = '" . $object1['Наименование'] . "' > " . $object1['Наименование'] . " </option>";
                            }
                            echo "</select>";
                            ?>
                        </th>
                        <th>
                            <?php
                            $querytype1 = "SELECT Сумма FROM Cost";
                            $resulttype1 = mysqli_query($link, $querytype1);
                            echo "<select id='selectsumm' class = 'selectNew' name = 'selectsumm' onchange='pip()'>";
                            echo "<option value=''>Выбор</option>";
                            while ($object1 = mysqli_fetch_array($resulttype1)) {
                                echo "<option value ='" . number_format($object1['Сумма'], 2, ',', ' ') . "' > " . number_format($object1['Сумма'], 2, ',', ' ') . " </option>";
                            }
                            echo "</select>";
                            ?>
                        </th>
                        <th>
                            <?php
                            $querytype = "SELECT * FROM `type`";
                            $resulttype = mysqli_query($link, $querytype);
                            ?>
                            <select id="selectType" class="selectNew" name="selectType" onchange='pip()'>
                                <option value=''>Выбор</option>
                                <?php
                                while ($object = mysqli_fetch_array($resulttype)) {
                                    echo "<option value = '" . $object['type'] . "' > " . $object['type'] . " </option>";
                                }
                                echo "</select>";
                                ?>
                        </th>
                        <th style="width: 200px;">
                            <?php
                            $querytype1 = "SELECT SubType FROM Subtype";
                            $resulttype1 = mysqli_query($link, $querytype1);
                            echo "<select id='selectSubtype' class = 'selectNew' name = 'selectSubtype' onclick='pip()'>";
                            echo "<option value=''>Выбор</option>";
                            while ($object1 = mysqli_fetch_array($resulttype1)) {
                                echo "<option value = '" . $object1['SubType'] . "' > " . $object1['SubType'] . " </option>";
                            }
                            echo "</select>";
                            ?>
                        </th>
                        <th>
                            <?php
                            $querytypeName = "SELECT `name` FROM `name`";
                            $resulttypeName = mysqli_query($link, $querytypeName);
                            echo "<select id='selectName' class = 'selectNew' name = 'selectName' onchange='pip()'>";
                            echo "<option value=''>Выбор</option>";
                            while ($objectName = mysqli_fetch_array($resulttypeName)) {
                                echo "<option value = '" . $objectName['name'] . "' > " . $objectName['name'] . " </option>";
                            }
                            echo "</select>";
                            ?>
                        </th>
                        <th>
                            <?php
                            $querytypeOrg = "SELECT org FROM org";
                            $resulttypeOrg = mysqli_query($link, $querytypeOrg);
                            echo "<select id='selectOrg' class = 'selectNew' name = 'selectOrg' onchange='pip()'>";
                            echo "<option value=''>Выбор</option>";
                            while ($objectOrg = mysqli_fetch_array($resulttypeOrg)) {
                                echo "<option value = '" . $objectOrg['org'] . "' > " . $objectOrg['org'] . " </option>";
                            }
                            echo "</select>";
                            ?>

                        </th>
                    </tr>
                </thead>
                <tbody id="FilterTable" style='word-break: break-all;'>
                    <?php
                    $newQuery = "SELECT * FROM Cost ORDER BY `Дата` ASC";
                    $result = mysqli_query($link, $newQuery);
                    while ($newrow = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td  style='display: none;'>" . $newrow['id'] . "</td>";
                        $date = date_create($newrow['Дата']);
                        $dateN = date_format($date, 'Y-m-d');
                        echo "<td>" . $dateN . "</td>";
                        echo "<td>" . $newrow['Наименование'] . "</td>";
                        echo "<td>" . number_format($newrow['Сумма'], 2, ',', ' ') . "</td>";
                        echo "<td>" . $newrow['type'] . "</td>";
                        echo "<td style='width: 25%;'>" . $newrow['Subtype'] . "</td>";
                        echo "<td>" . $newrow['name'] . "</td>";
                        echo "<td>" . $newrow['org'] . "</td>";
                        echo "</tr>";
                        // number_format($newrow['Сумма'], 2, ',', ' ')
                    };
                    ?>
                </tbody>
            </table>
            <button class="toBottom"><img src="/png/arrow-down-removebg-preview.png" width="30%"></button>

            <div id="zatemnenie">
                <window> <a id="closeX" class="close_window">X</a>
                    <form method="post" id="form_menu"></form>
                    Изменение позиции: <label id="tyt" style="color: blue;"></label>
                    <ul style="text-align: left;">
                        <input type="hidden" name="id" id="hidd_id" form='form_menu'>
                        <li>Дата: </li><input type="date" name="change_date" class="input_cost" id="change_date" form='form_menu' style="text-align: left;" readonly>

                        <li> Наименование: </li><input type="text" class="input_cost" name="change_name" id="change_name" form='form_menu' readonly>

                        <li>Сумма:</li> <input type="number" name="summ" class="input_cost" id="summ" step="0.01" min="0" form='form_menu' readonly>
                        <li>Тип:</li>
                        <?php
                        $querytype = "SELECT * FROM `type`";
                        $resulttype = mysqli_query($link, $querytype);

                        echo '<select id="typeS" class="selectS" name="selectType" form="form_menu">';
                        echo '<option value="0">Выбор</option>';
                        while ($object = mysqli_fetch_array($resulttype)) {
                            echo "<option value = '" . $object['type'] . "' > " . $object['type'] . " </option>";
                        }
                        echo "</select>";
                        ?>

                        <li>Подтип:</li>
                        <?php
                        $querytype1 = "SELECT SubType FROM Subtype";
                        $resulttype1 = mysqli_query($link, $querytype1);
                        echo "<select id='subtypeS' class = 'selectS' name = 'selectSubtype' form='form_menu'>";
                        echo "<option value='0'>Выбор</option>";
                        while ($object1 = mysqli_fetch_array($resulttype1)) {
                            echo "<option value = '" . $object1['SubType'] . "' > " . $object1['SubType'] . " </option>";
                        }
                        echo "</select>";
                        ?>
                        <li>Имя:</li>
                        <?php
                        $querytypeName = "SELECT `name` FROM `name`";
                        $resulttypeName = mysqli_query($link, $querytypeName);
                        echo "<select id='nameS' class = 'selectS' name = 'selectName' form='form_menu'>";
                        echo "<option value='0'>Выбор</option>";
                        while ($objectName = mysqli_fetch_array($resulttypeName)) {
                            echo "<option value = '" . $objectName['name'] . "' > " . $objectName['name'] . " </option>";
                        }
                        echo "</select>";
                        ?>
                        <li>Организация:</li>
                        <?php
                        $querytypeOrg = "SELECT org FROM org";
                        $resulttypeOrg = mysqli_query($link, $querytypeOrg);
                        echo "<select id='orgS' class = 'selectS' name = 'selectOrg' form='form_menu'>";
                        echo "<option value='0'>Выбор</option>";
                        while ($objectOrg = mysqli_fetch_array($resulttypeOrg)) {
                            echo "<option value = '" . $objectOrg['org'] . "' > " . $objectOrg['org'] . " </option>";
                        }
                        echo "</select>";
                        ?>
                    </ul>
                    <input type="submit" value="Сохранить" class="button_check" id="submit_menu" name="submit_menu" form='form_menu'>
                    <button id="button_change" class="button_check" style="margin-left: 40px;" onclick="window.location.href = 'EditCost.php&id=';">Разделить</button>
                </window>
            </div>
        <?php
        mysqli_close($link);
    } else {
        tologin();
    }
        ?>
        </div>
        <script src="js/jquery-3.6.0.min.js"></script>
        <script src="js/jquery.sumoselect.js"></script>
        <script src="js/toast.min.js"></script>
        <script src="js/filter.js"></script>
        <script src="js/menu.js"></script>
        <script src="js/js_dateFormat.js"></script>
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
                    autohide: true,
                    interval: 10000,
                  });</script>';
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.selectNew').SumoSelect({
                    search: true,
                });
                $('.selectS').SumoSelect({
                    search: true
                });
                $('.subtypeS').SumoSelect({
                    search: true
                });
                $('.nameS').SumoSelect({
                    search: true
                });
                $('.orgS').SumoSelect({
                    search: true
                });
            })

            $('.toBottom').on('click', function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 600);
                return false;
            })
            jQuery(function(f) {
                var element = f('.toBottom');
                f(window).scroll(function() {
                    element['fade' + (f(this).scrollTop() > 200 ? 'In' : 'Out')](500);
                });
            });

            Date.prototype.format = function(mask, utc) {
                return dateFormat(this, mask, utc);
            };

            let table_filter = document.getElementById("FilterTable");
            let closeX = document.getElementById("closeX");
            let modal_window = document.getElementById("zatemnenie");
            let change_date = document.getElementById("change_date");
            let tyt = document.getElementById("tyt");
            let change_name = document.getElementById("change_name");
            let summ = document.getElementById("summ");
            let id = document.getElementById("hidd_id");
            let button_change = document.getElementById("button_change");
            // let tyt = document.getElementById("tyt");
            // let hiden_name = document.getElementById("hiden_name");
            // let tr_table;
            // let select_status = document.getElementById("Status_menu");
            // let select_manager = document.getElementById("manager_menu");


            table_filter.addEventListener("dblclick", function(event) {


                tr_table = event.target.closest("tr");
                if (!tr_table) return;
                id.value = tr_table.cells[0].textContent;
                let id_to = tr_table.cells[0].textContent;
                let date = tr_table.cells[1].textContent;
                // let date_new = new Date(date);
                $('#tyt').text(tr_table.cells[2].textContent);
                change_date.value = date;
                change_name.value = tr_table.cells[2].textContent;
                let summ_let = tr_table.cells[3].textContent;
                summ_let = summ_let.replace(/\s/g, '');
                summ_let = parseFloat(summ_let.replace(",", "."));
                summ.value = summ_let;
                let typeN = tr_table.cells[4].textContent;
                let subtypeN = tr_table.cells[5].textContent;
                let nameN = tr_table.cells[6].textContent;
                let orgN = tr_table.cells[7].textContent;
                $("select.selectS")[0].sumo.selectItem(typeN);
                $("select.selectS")[1].sumo.selectItem(subtypeN);
                $("select.selectS")[2].sumo.selectItem(nameN);
                $("select.selectS")[3].sumo.selectItem(orgN);

                $('#button_change').attr('onclick', "window.location.href = 'EditCost.php?id=" + id_to + "'");

                modal_window.style = "display:block";
            });

            closeX.addEventListener("click", function(func) {
                change_date.value = "";
                change_name.value = "";
                summ.value = "";
                // $("select.Status_menu")[0].sumo.selectItem(0);
                // $("select.manager_menu")[0].sumo.selectItem(0);
                $("select.selectS")[0].sumo.selectItem(0);
                $("select.selectS")[1].sumo.selectItem(0);
                $("select.selectS")[2].sumo.selectItem(0);
                $("select.selectS")[3].sumo.selectItem(0);
                modal_window.style = "display:none";
            })
        </script>
    </body>

    </html>