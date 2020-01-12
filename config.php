<?php

/**
 *
*/

$_ROOT = '/DJJ';

define('DB_UN', 'root');
define('DB_PW', 'admin');
define('DB_DB', 'djj_db');

// make sure this is an IP and not a hostname
// hostname resolution adds ~2s processing time
define('DB_HN', '127.0.0.1');

spl_autoload_register(function ($class_name) {
    @include_once('class/'.$class_name.'.php');
    //@include_once('./class/'.$class_name.'.php');
   // @include_once('../class/'.$class_name.'.php');
});

 ?>
