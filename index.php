<?php
include_once('config.php');
session_start();

setlocale(LC_ALL, 'ru_RU.UTF-8', 'Russian_Russia.65001');
header('Content-type: text/html; charset=utf-8');

$q = isset($_GET['q']) ? $_GET['q'] : '';
$rout = new Rout($q);
$rout->Request();
