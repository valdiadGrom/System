<?php
session_start();
include_once "link.php";

if (isset($_POST['submit'])) {
    $query = mysqli_query($link, "SELECT user_id, user_hash FROM users WHERE user_login='" . mysqli_real_escape_string($link, $_POST['login']) . "' LIMIT 1");
    $data = mysqli_fetch_array($query);
    $pass = $_POST['password'];

    if (password_verify($pass, $data['user_hash'])) {
        $_SESSION['auth'] = true;
        $_SESSION['id'] = $data['user_id'];
        header("Location: mainmenu.php");
        exit();
    } else {
        echo "<script> alert('Вы ввели неправильный логин/пароль') </script>";
    }
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Авторизация</title>
    <meta charset="utf-8" />
    <link href="css/style.css" rel="stylesheet" />
    <link rel="icon" href="../png/favicon.ico" type="image/x-icon">
</head>

<body>
    <div class="login_page">
        <div class="login">
            <form method="POST">
                <tr>
                    <th><input placeholder="Логин" name="login" type="text" required></th>
                </tr>
                <tr>
                    <th><input placeholder="Пароль" name="password" type="password" required></th>
                </tr>
                <tr>
                    <th class="button"><input name="submit" type="submit" value="Войти"></th>
                </tr>
            </form>
        </div>
    </div>

</body>

</html>