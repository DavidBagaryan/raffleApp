<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.04.2018
 * Time: 15:38
 */

use app\models\Helper;
use app\models\Renderer;
use app\models\authServices\AuthService;

require_once 'index.php';
require_once 'config.php';


spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $class = substr($class, 4);
    require_once "{$class}.php";
});

try {
    $indexContent = new Renderer('index.html');

    $menu = is_null($loggedUser)
        ? new Renderer('authMenu.html') : new Renderer('loggedUser.html');

    $authService = AuthService::check();

    $content = is_null($loggedUser) ?
        new Renderer($authService->getServiceTemplate()) : new Renderer('raffle.html');
} catch (Exception $e) {
    $e->getMessage();
} finally {
    echo $indexContent->render([
        'menu' => $menu->render(['userLogin' => Helper::mbUcFirst($loggedUser['user_login'])]),
        'module' => $content->render($userDefaultParams),
        'errors' => $authService->action(),
    ]);
}

var_dump($_POST);
