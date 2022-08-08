<?php
require 'C:\OpenServer\vendor\autoload.php';
// require '/var/www/s182635/data/bin/vendor/autoload.php';
include_once "link.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet; // Работа со структурой документа
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Запись в нужный формат. Если мы говорим только об excel то там может быть ещё Xls

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Дата');
$sheet->setCellValue('B1', 'Наименование');
$sheet->setCellValue('C1', 'Сумма');
$sheet->setCellValue('D1', 'Тип');
$sheet->setCellValue('E1', 'Подтип');
$sheet->setCellValue('F1', 'Имя');
$sheet->setCellValue('G1', 'Организация');

$sheet->getColumnDimension('A')->setWidth(11);
$sheet->getColumnDimension('B')->setWidth(50);
$sheet->getColumnDimension('C')->setWidth(12);
$sheet->getColumnDimension('D')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(10);
$sheet->getColumnDimension('F')->setWidth(10);
$sheet->getColumnDimension('G')->setWidth(10);

$query = "SELECT * FROM `Cost` ORDER BY `Дата` ASC";
$result = mysqli_query($link, $query);
$i = 2;
while ($row = mysqli_fetch_array($result)) {
    $date = date_create($row['Дата']);
    $dateN = date_format($date, 'd-m-Y');
    $sheet->setCellValue('A' . $i . '', $dateN);
    $sheet->setCellValue('B' . $i . '', $row['Наименование']);
    $sheet->setCellValue('C' . $i . '', $row['Сумма']);
    $sheet->setCellValue('D' . $i . '', $row['type']);
    $sheet->setCellValue('E' . $i . '', $row['Subtype']);
    $sheet->setCellValue('F' . $i . '', $row['name']);
    $sheet->setCellValue('G' . $i . '', $row['org']);
    $i++;
}

// Структура документа

try {
    $writer = new Xlsx($spreadsheet);
    $writer->save('Cost.xlsx');
} catch (PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
    echo $e->getMessage();
}
$file = 'Cost.xlsx';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}
