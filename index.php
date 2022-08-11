<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');
ini_set("max_execution_time", "1800");
ini_set('allow_url_fopen', 1);

require "vendor/autoload.php";
require "config.php";

use PHPHtmlParser\Dom;

$catalogs_content = file_get_contents($catologs);
$dom = new Dom;
$dom2 = new Dom;
$dom->loadStr($catalogs_content);
$catalogs_lists = $dom->find('#catalog_category .subsection')[0];
$catalogs_href = [];
foreach ($catalogs_lists as $a) {
   if ($a->getAttribute('href')) {
      $key = $site . $a->getAttribute('href');
      $catalogs_href[$key] = [];
      $dom2->loadStr(file_get_contents($key));
      $pod_catalogs_lists = $dom2->find('#catalog_category .subsection')[0];
      if ($pod_catalogs_lists) {
         foreach ($pod_catalogs_lists as $link) {
            if ($link->getAttribute('href')) {
               $catalogs_href[$key][] = $site . $link->getAttribute('href');
            }
         }
      }
   }
}

echo json_encode($catalogs_href);
