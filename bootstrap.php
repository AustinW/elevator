<?php

require_once 'vendor/autoload.php';

if (!function_exists('log_msg')) {
    function log_msg($message, $class = '') {
        echo (!empty($class)) ? strtoupper($class) . ": " . $message : $message;
        echo "\n";
    }
}