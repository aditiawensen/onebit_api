<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);

$isi = "\usd";
echo str_replace("\\", ":backslash:", $isi);