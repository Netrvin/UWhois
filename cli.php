<?php

include_once 'UWhois.php';

if (preg_match("/cli/i", php_sapi_name())) {
    $Whois = new UWhois;
    echo $Whois->get($argv[1]);
}else{
    echo 'Please run in console';
}