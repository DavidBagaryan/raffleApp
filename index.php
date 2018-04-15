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

    if (!is_null($_SESSION['loggedUser'])) {
        $userDefaultParams['id'] = $_SESSION['loggedUser']['id'];
        $userDefaultParams['userLogin'] = $_SESSION['loggedUser']['user_login'];
    }

    $raffle = Raffle::check();

    if (!is_null($_SESSION['gift'])){
//        $userDefaultParams['giftType'] = $_SESSION['gift']['type'];
        $userDefaultParams['giftValue'] = $_SESSION['gift']['value'];
    }


    $indexContent = new Renderer('index.html');

    if (!is_null($_SESSION['loggedUser'])) {
        $menu = new Renderer('loggedUser.html');
        $content = is_null($_SESSION['gift']['lastToken'])
            ? new Renderer('raffle.html') : new Renderer('userChoice.html');
    } else {
        $menu = new Renderer('authMenu.html');
        $content = new Renderer($authService->getServiceTemplate());
    }

} catch (Exception $e) {
    $e->getMessage();
} finally {
    echo $indexContent->render([
        'menu' => $menu->render(['userLogin' => Helper::mbUcFirst($_SESSION['loggedUser']['user_login'])]),
        'module' => $content->render($userDefaultParams),
        'errors' => $authService->action(),
    ]);
}

var_dump($_SESSION);
