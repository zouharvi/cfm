<?php

if (!file_exists('logs/')) {
    mkdir('logs/');
}

$path = 'logs/' . date('Y-m-d') . '.log';

if (!file_exists($path)) {
    $file = fopen($path, 'w');
    fwrite($file, '1'); 
} else {
    $file  = fopen( $path, 'r' );
    $count = fgets( $file, 1000 );
    fclose( $file );

    $count = abs( intval( $count ) ) + 1;

    $file = fopen( $path, 'w' );
    fwrite( $file, $count );
    fclose( $file );
}
?>
