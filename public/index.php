<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('my_log');
$log->pushHandler(new StreamHandler('log/my.log', Logger::DEBUG));

$timeStart = microtime();
$memoryStart = memory_get_usage();

for ($i = 0; $i < 10; $i++) {
	echo $i;
}

$timeEnd = microtime();

$log->debug("start", [
	sprintf("time: %s", $timeEnd - $timeStart)
]);

$memoryEnd = memory_get_usage();

$log->debug("memory", [
	"Начало отсечки времени:", $memoryStart,
	"Конец отсечки времени:", $memoryEnd,
	sprintf("Разница:", $memoryEnd - $memoryStart)
]);