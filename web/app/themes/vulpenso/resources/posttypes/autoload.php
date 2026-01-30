<?php
$dir   = __DIR__ . '/';
$files = scandir($dir);
foreach( $files as $file ) {
    if ( $file == '.' || $file == '..' || $file == basename(__FILE__) || $file == '.DS_Store' ) {
        continue;
    }
    require_once( __DIR__ . '/' . $file );
}
