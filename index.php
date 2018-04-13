<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.04.2018
 * Time: 15:38
 */

use app\models\Renderer;

session_start();

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $class = substr($class, 4);
    require_once "{$class}.php";
});

$content = new Renderer('index.html');

$params = [
    'login' => $_POST['login'] ? $_POST['login'] : null,
    'pass' => $_POST['pass'] ? $_POST['pass'] : null,
    'rememberMe' => $_POST['rememberMe'] ? 'checked' : null,
];

echo $content->render($params);
