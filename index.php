<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');
ini_set("max_execution_time", "10000");
ini_set('allow_url_fopen', 1);
ini_set('memory_limit', '10000M');

require "vendor/autoload.php";

use PHPHtmlParser\Dom;
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
$writer->save('p.xlsx');
$site = "https://www.planeta-sirius.ru";
$catalogs_content = file_get_contents("https://www.planeta-sirius.ru/katalog/");
$dom = new Dom;
$dom->loadStr($catalogs_content);
$catalogs_lists = $dom->find('#catalog_category .subsection')[0];
$catalogs_href = [];
unset($dom);
foreach ($catalogs_lists as $a) {
   $dom2 = new Dom;
   if ($a->getAttribute('href')) {
      $key = $site . $a->getAttribute('href');
      unset($a);
      $catalogs_href[$key] = [];
      $dom2->loadStr(file_get_contents($key));
      $pod_catalogs_lists = $dom2->find('#catalog_category .subsection')[0];
      unset($dom2);
      if ($pod_catalogs_lists) {
         foreach ($pod_catalogs_lists as $link) {
            if ($link->getAttribute('href')) {
               $catalogs_href[$key][] = $site . $link->getAttribute('href');
               unset($link);
            }
         }
      }
   }
   unset($dom2);
}
$count = 2;
foreach ($catalogs_href as $key => $href) {
   $dom = new Dom;
   if (count($href) > 0) {
      foreach ($href as $link) {
         $content = file_get_contents($link);
         unset($link);
         $dom->loadStr($content);
         $cards = $dom->find('#catalog_category .row')[1];
         unset($dom);
         unset($content);
         foreach ($cards as $card) {
            $dom = new Dom;
            $article = $card->find('.artikul')[0];
            $article = strip_tags($article);
            $a = $card->find('h4 a')[0];
            $price_opt = $card->find('.price-opt')[0];
            $price_rozn = $card->find('.price-rozn')[0];
            $image = $card->find('a img')[0];
            if ($image) {
               $image = $image->getAttribute('src');
            }
            if ($a) {
               $href = $a->getAttribute('href');
               $product = file_get_contents($site . $href);
               $dom->loadStr($product);
               unset($product);
               $desc = $dom->find('.product_description')[0];
               unset($dom);
               $desc = $desc->text;
               $title = $a->text;
               unset($a);
               $price_opt = str_replace('Цена', '', strip_tags($price_opt));
               $price_opt = str_replace("&#8381;", '₽', strip_tags($price_opt));
               $price_rozn = str_replace('Цена', '', strip_tags($price_rozn));
               $price_rozn = str_replace("&#8381;", '₽', strip_tags($price_rozn));
               $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
               $spreadsheet = $reader->load("p.xlsx");
               unset($reader);
               $sheet = $spreadsheet->getActiveSheet();
               $sheet->setCellValue('A' . $count, $article);
               $sheet->setCellValue('B' . $count, htmlspecialchars_decode($title));
               $sheet->setCellValue('C' . $count, $desc);
               $sheet->setCellValue('D' . $count, $price_opt);
               $sheet->setCellValue('E' . $count, $price_rozn);
               $sheet->setCellValue('F' . $count, $site . $image);
               $writer = new Xlsx($spreadsheet);
               $writer->save('p.xlsx');
               $count++;
               // if ($count > 10) die;
               echo $count . "<br>";
               unset($sheet);
               unset($writer);
               unset($spreadsheet);
            }
            unset($card);
         }
      }
   } else {
      $dom = new Dom;
      $content = file_get_contents($key);
      $dom->loadStr($content);
      unset($content);
      $cards = $dom->find('#catalog_category .row')[1];
      foreach ($cards as $card) {
         $article = $card->find('.artikul')[0];
         $article = strip_tags($article);
         $a = $card->find('h4 a')[0];
         $price_opt = $card->find('.price-opt')[0];
         $price_rozn = $card->find('.price-rozn')[0];
         $image = $card->find('a img')[0];
         if ($image) {
            $image = $image->getAttribute('src');
         }
         if ($a) {
            $href = $a->getAttribute('href');
            $product = file_get_contents($site . $href);
            $dom->loadStr($product);
            $desc = $dom->find('.product_description')[0];
            $desc = $desc->text;
            $title = $a->text;
            $price_opt = str_replace('Цена', '', strip_tags($price_opt));
            $price_opt = str_replace("&#8381;", '₽', strip_tags($price_opt));
            $price_rozn = str_replace('Цена', '', strip_tags($price_rozn));
            $price_rozn = str_replace("&#8381;", '₽', strip_tags($price_rozn));
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load("p.xlsx");
            unset($reader);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A' . $count, $article);
            $sheet->setCellValue('B' . $count, htmlspecialchars_decode($title));
            $sheet->setCellValue('C' . $count, $desc);
            $sheet->setCellValue('D' . $count, $price_opt);
            $sheet->setCellValue('E' . $count, $price_rozn);
            $sheet->setCellValue('F' . $count, $site . $image);
            $writer = new Xlsx($spreadsheet);
            $writer->save('p.xlsx');
            $count++;
            // if ($count > 10) die;
            echo $count . "<br>";
            unset($writer);
            unset($spreadsheet);
            unset($sheet);
         }

         unset($card);
      }
      unset($dom);
   }
}


// echo $count;
// echo htmlspecialchars($cards);


// app.listen(443, (function(req, res) {
//   console.log(`Example app listening on port`)
// }))

// app.use(function (req, res, next) {

//     // Website you wish to allow to connect
//     res.setHeader('Access-Control-Allow-Origin', '*');

//     // Request methods you wish to allow
//     res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');

//     // Request headers you wish to allow
//     res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');

//     // Set to true if you need the website to include cookies in the requests sent
//     // to the API (e.g. in case you use sessions)
//     res.setHeader('Access-Control-Allow-Credentials', true);

//     // Pass to next layer of middleware
//     next();
// });