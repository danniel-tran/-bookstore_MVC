<?php
require_once 'define.php';
error_reporting(error_reporting() & ~E_NOTICE);
date_default_timezone_set(DEFAULT_TIMEZONE);


function __autoload($clasName)
{
	$fileName = PATH_LIBRARY . "{$clasName}.php";
	if (file_exists($fileName)) require_once $fileName;
}
Session::init();

$bootstrap = new Bootstrap();
$bootstrap->init();
