<?php
//incapsula
define('HEADER_NAME', 'HTTP_INCAP_CLIENT_IP');
try {
    //stop process if there is no header
    if (empty($_SERVER[HEADER_NAME])) throw new Exception('No header defined', 1);

    //validate header value
    if (function_exists('filter_var')) {
        $ip = filter_var($_SERVER[HEADER_NAME], FILTER_VALIDATE_IP);
        if (false === $ip) throw new Exception('The value is not a valid IP address', 2);
    } else {
        $ip = trim($_SERVER[HEADER_NAME]);
        if (false === preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $ip)) throw new Exception('The value is not a valid IP address', 2);
    }

    //At this point the initial IP value is exist and validated
    $_SERVER['REMOTE_ADDR'] = $ip;
} catch (Exception $e) {
    //echo $e->getMessage();
}
