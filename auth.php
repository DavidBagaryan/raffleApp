<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.04.2018
 * Time: 16:03
 */

use app\models\authServices\AuthService;
use app\models\Renderer;

require_once 'index.php';
require_once 'config.php';


try {
    $authService = AuthService::check();
    $content = new Renderer($authService->getServiceTemplate());
    $params = [
        'login' => $_POST['login'] ? $_POST['login'] : null,
        'pass' => $_POST['pass'] ? $_POST['pass'] : null,
        'pass2' => $_POST['pass2'] ? $_POST['pass2'] : null,
        'rememberMe' => $_POST['rememberMe'] ? 'checked' : null,
    ];
    echo $content->render($params);
    $authService->auth();
} catch (Exception $e) {
    $e->getMessage();
}
//finally {
//
//}

var_dump($_POST);
