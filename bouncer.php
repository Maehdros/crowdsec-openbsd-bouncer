<?php

require "vendor/autoload.php";

$envdir = dirname(__FILE__);
if(!file_exists("$envdir/.env")){
   die("Missing .env file. Please create it and add APIKEY=.......");
}
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__));
$dotenv->load();

$options = getopt("",["startup","debug"]);

if(!isset($_ENV["APIKEY"])){
   die("Missing APIKEY in .env file");
}
if(!isset($_ENV["LAPIURL"])){
   die("Missing LAPIURL in .env file");
}
if(!isset($_ENV["PFTABLE"])){
   die("Missing PFTABLE in .env file");
}

$startup="false";
if(isset($options["startup"])){
   $startup="true";
}
$debug=false;
if(isset($options["debug"])){
   $debug="true";
}

$ch = curl_init($_ENV["LAPIURL"]."/v1/decisions/stream?startup=$startup");
$headers = array(
   "X-Api-Key: {$_ENV["APIKEY"]}",
   "Content-Type: application/json",
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch,CURLOPT_SSL_VERIFYSTATUS,false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch, CURLOPT_USERAGENT, "crowdsec-openbsd-bouncer/0.0.1");
$response = curl_exec($ch);
$arr = json_decode($response);
if($debug){
   print_r($arr);
}
$ipTxt="";
if(isset($arr->deleted)) {
   foreach ($arr->deleted as $obj) {
      $ipTxt .= $obj->value . PHP_EOL;
   }
   file_put_contents("/tmp/ip.txt", $ipTxt);
   exec("/sbin/pfctl -t {$_ENV["PFTABLE"]} -T delete -f /tmp/ip.txt");
}
if(isset($arr->new)) {
   $ipTxt = "";
   foreach ($arr->new as $obj) {
      $ipTxt .= $obj->value . PHP_EOL;
   }
   file_put_contents("/tmp/ip.txt", $ipTxt);
   exec("/sbin/pfctl -t {$_ENV["PFTABLE"]} -T add -f /tmp/ip.txt");
}
if(file_exists("/tmp/ip.txt")){
   unlink("/tmp/ip.txt");
}