<?php
ob_start();
header('Content-Type: text/html; charset=utf-8');

#ini_set('error_reporting', E_ALL);
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);

$_mainMenu = array(
	"/" => "API каталога",
	"/bd/" => "База данных",
	"/import/" => "Импорт данных",
	"/export/" => "Экспорт данных"
);

require_once(__DIR__."/class.php");

$api = new BlackWolf\Api(true);
