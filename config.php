<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 13.04.2018
 * Time: 17:58
 */

session_start();

// DB defines
define('HOST', 'localhost');
define('DB_NAME', 'testDB');
define('USER', 'root');
define('PASSWORD', '');
define('CHARSET', 'utf8');

// USER DEFAULT PARAMS

$userDefaultParams = [
    'login' => $_POST['login'] ? $_POST['login'] : '',
    'address' => $_POST['address'] ? $_POST['address'] : '',
    'bankDetails' => $_POST['bankDetails'] ? $_POST['bankDetails'] : '',
    'pass' => $_POST['pass'] ? $_POST['pass'] : '',
    'pass2' => $_POST['pass2'] ? $_POST['pass2'] : '',

    'randomGift' => rand(1, 3),
    'giftToken' => rand(10000, 99999),
];

