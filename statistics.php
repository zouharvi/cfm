<?php

$files = glob('logs/*.log');

foreach(array_reverse($files) as $logFile) {
    echo ltrim(rtrim($logFile, ".log"), "logs/") . ": " . file_get_contents($logFile) . "<br>";
}

?>
