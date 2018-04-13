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
    $authService = AuthService::check();

    $authService->auth();
    $authContent = new Renderer($authService->getServiceTemplate());

    echo $indexContent->render([
        'module' => $authContent->render($userDefaultParams)
    ]);

} catch (Exception $e) {
    $e->getMessage();
}
//finally {
//
//}

var_dump($_POST);

