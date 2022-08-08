<?php
// $host = '81.90.180.80:3316';
// $user = 'profit';
// $password = 'Profit2018';
// $db_name = 'profiteng';

$host = '127.0.0.1:3306';
$user = 'mysql';
$password = '';
$db_name = 'profit';

$link = mysqli_connect($host, $user, $password, $db_name);

if ($link === false) {
    die("Ошибка: " . mysqli_connect_error());
}
