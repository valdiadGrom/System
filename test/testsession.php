<?php
session_start();
if (!isset($_SESSION['time'])) {
    $_SESSION['time'] = date("H:i:s");
}

$dataS = '2018-10-01';
$dataE = '2021-08-03';
$dataMonths = array();
$i = 0;
$dataMonths[$i] = date('Y-m', strtotime($dataS));
$i++;
while (strtotime($dataS) < strtotime($dataE)) {
    $dataPer = strtotime('+1 MONTH', strtotime($dataS));
    $dataS = date('Y-m-d', $dataPer);
    $dataMonths[$i] = date('Y-m', $dataPer);
    $i++;
}
foreach ($dataMonths as $dmonth) {
    echo $dmonth . "<br>";
}
