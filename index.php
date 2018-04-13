<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.04.2018
 * Time: 15:38
 */

use app\models\Renderer;
use app\models\authServices\AuthService;

require_once 'index.php';
require_once 'config.php';


session_start();

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $class = substr($class, 4);
    require_once "{$class}.php";
});

try {
    $indexContent = new Renderer('index.html');
    $authMenu = is_null($loggedUser = $_SESSION['loggedUser'])
        ? new Renderer('authMenu.html') : new Renderer('loggedUser.html');

    $authService = AuthService::check();
    $authContent = new Renderer($authService->getServiceTemplate());
} catch (Exception $e) {
    $e->getMessage();
} finally {
    echo $indexContent->render([
        'authMenu' => $authMenu->render(['user' => $loggedUser['user_login']]),
        'module' => $authContent->render($userDefaultParams),
        'errors' => $authService->action(),
    ]);
}

