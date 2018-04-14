<?php
/**
 * Created by PhpStorm.
 * User: user
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
$loggedUser = $_SESSION['loggedUser'];

$userDefaultParams = [
    'login' => $_POST['login'],
    'pass' => $_POST['pass'],
    'pass2' => $_POST['pass2'],
    'id' => $loggedUser['id'],
    'userLogin' => $loggedUser['user_login'],
    'randomGift' => rand(1,3),
    'giftToken' => rand(10000,99999),
    'gift' => $_SESSION['giftValue']
];

