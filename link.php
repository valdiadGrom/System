<?php


$host = '&&&&&&&&&&&&';
$user = '&&&&&';
$password = '&&&&&';
$db_name = '&&&&&';

$link = mysqli_connect($host, $user, $password, $db_name);

if ($link === false) {
    die("Ошибка: " . mysqli_connect_error());
}
