<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(100);
$sheet->getColumnDimension('C')->setWidth(150);
$sheet->getColumnDimension('D')->setWidth(200);

$writer = new Xlsx($spreadsheet);
$writer->save('hello world.xlsx');
