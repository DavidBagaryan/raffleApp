<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.04.2018
 * Time: 15:38
 */

use app\models\Helper;
use app\models\Raffle;
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
    $authService = AuthService::check();
    $raffle = Raffle::check();

    $indexContent = new Renderer('index.html');

    if (is_null($loggedUser)) {
        $menu = new Renderer('authMenu.html');
        $content = new Renderer($authService->getServiceTemplate());
    } else {
        $menu = new Renderer('loggedUser.html');
        $content = is_null($_SESSION['giftToken'])
            ? new Renderer('raffle.html') : new Renderer('userChoice.html');
    }

} catch (Exception $e) {
    $e->getMessage();
} finally {
    echo $indexContent->render([
        'menu' => $menu->render(['userLogin' => Helper::mbUcFirst($loggedUser['user_login'])]),
        'module' => $content->render($userDefaultParams),
        'errors' => $authService->action(),
    ]);
}

var_dump($_SESSION, $_POST, $content, $authService);
