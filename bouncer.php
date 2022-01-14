<?php
if(!file_exists(".env")){
   die("Missing .env file. Please create it and add APIKEY=.......");
}
require "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if(!isset($_ENV["APIKEY"])){
   die("Missing APIKEY in .env file");
}
$ch = curl_init($_ENV["LAPIURL"]."/v1/decisions");
$headers = array(
   "X-Api-Key: {$_ENV["APIKEY"]}",
   "Content-Type: application/json",
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch,CURLOPT_SSL_VERIFYSTATUS,false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
$response = curl_exec($ch);
$arr = json_decode($response);
foreach ($arr as $obj){
   $cmd = "/sbin/pfctl -t {$_ENV["PFTABLE"]} -T add {$obj->value}";
   echo $cmd.PHP_EOL;
}