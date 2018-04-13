<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.04.2018
 * Time: 17:58
 */


// DB defines
define('HOST', 'localhost');
define('DB_NAME', 'testDB');
define('USER', 'root');
define('PASSWORD', '');
define('CHARSET', 'utf8');

// USER DEFAULT PARAMS
$userDefaultParams = [
    'login' => $_POST['login'],
    'pass' => $_POST['pass'],
    'pass2' => $_POST['pass2'],
    'rememberMe' => $_POST['rememberMe'],
];