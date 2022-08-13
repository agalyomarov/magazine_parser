<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(70);
$sheet->getColumnDimension('C')->setWidth(100);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(100);

$sheet->setCellValue('A1', 'Артикул');
$sheet->setCellValue('B1', 'Название');
$sheet->setCellValue('C1', 'Описание ');
$sheet->setCellValue('D1', 'Цена-1 ');
$sheet->setCellValue('E1', 'Цена-2 ');
$sheet->setCellValue('F1', 'ссылка на фото товара-1');

$writer = new Xlsx($spreadsheet);
$writer->save('hello world.xlsx');
