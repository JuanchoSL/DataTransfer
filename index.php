<?php
use JuanchoSL\DataTransfer\ArrayExtractor;
use JuanchoSL\DataTransfer\JsonObjectExtractor;
use JuanchoSL\DataTransfer\ObjectExtractor;

include_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

//$arr = ['index' => 'value'];
//$json = json_encode($arr);
//$obj = new JsonObjectExtractor($json);
//$obj = new ObjectExtractor(json_decode($json, false));
//$obj = new ArrayExtractor($arr);
//echo $obj->has('index');
//print_r($_SERVER);exit;
//echo htmlspecialchars($argv[1]);exit;

$obj = new ArrayExtractor($_SERVER);
echo $obj->get('OS');