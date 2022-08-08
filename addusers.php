<?php
session_start();
function tologin()
{
  header("Location: index.php");
  exit();
}
if (!empty($_SESSION['auth'])) {
  $err = array();
  $success = array();

  $host = '81.90.180.80:3316';
  $user = 'profit';
  $password = 'Profit2018';
  $db_name = 'profiteng';

  $link = mysqli_connect($host, $user, $password, $db_name);

  if ($link === false) {
    die("Ошибка: " . mysqli_connect_error());
  }



  #Создание нового пользователя


  if (isset($_POST['login'])) {

    $role = $_POST['user_role'];

    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) {
      $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    if (strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30) {
      $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    $query = mysqli_query($link, "SELECT user_id FROM users WHERE user_login='" . mysqli_real_escape_string($link, $_POST['login']) . "'");
    if (mysqli_num_rows($query) > 0) {
      $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    $resultRole = mysqli_query($link, "SELECT `role_name` FROM `Roles` WHERE `role_full_name` = '$role';");
    if (mysqli_num_rows($resultRole) > 0) {
      while ($row = mysqli_fetch_array($resultRole)) {
        $user_role = $row['role_name'];
      }
    } else {
      $err[] = "Выберите роль пользователя";
    }


    if (count($err) == 0) {

      $login = $_POST['login'];

      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

      mysqli_query($link, "INSERT INTO users SET user_login='" . $login . "', user_hash='" . $password . "', user_role ='" . $user_role . "'");

      $success[] = "Пользователь " . $login . " успешно добавлен";
      exit();
    }
  }
  // mysqli_close($link);
} else {
  tologin();
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Добавление пользователя</title>
  <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/newProject.css" rel="stylesheet" />
  <link href="css/toast.min.css" rel="stylesheet">
  <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
  <link href="css/nav.css" rel="stylesheet" />
</head>

<body>
  <?php include_once "sidenav.php"; ?>
  <div class="main">
    <h1>Добавление пользователя</h1>
    <div class="login">
      <form method="post">
        <tr>
          <th><input type="text" name="fio" placeholder="ФИО" required></th>
        </tr>
        <tr>
          <th><input type="text" name="login" placeholder="Логин" required></th>
        </tr>
        <tr>
          <th><input type="password" name="password" placeholder="Пароль" required></th>
        </tr>
        <tr>
          <th>
            <select name="user_role" required="required">
              <option value="">Выберите роль</option>
              <?php
              $resultCheckRole = mysqli_query($link, "SELECT `role_full_name` FROM `Roles`;");
              while ($rowR = mysqli_fetch_array($resultCheckRole)) {
                echo "<option value='" . $rowR['role_full_name'] . "'>" . $rowR['role_full_name'] . "</option>";
              }
              mysqli_close($link);
              ?>
            </select>
          </th>
        </tr>
        <tr>
          <th><input type="submit" value="Добавить"></th>
        </tr>
      </form>
    </div>
  </div>
  <script src="js/toast.min.js"></script>
  <script src="js/menu.js"></script>
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