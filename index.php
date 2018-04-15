<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.04.2018
 * Time: 15:38
 */

use app\models\authServices\LogoutService;
use app\models\gifts\Gift;
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
    $gift = Gift::check();

    //add some variables for user params
    if (!is_null($_SESSION['loggedUser'])) {
        $userDefaultParams['id'] = $_SESSION['loggedUser']['id'];
        $userDefaultParams['userLogin'] = $_SESSION['loggedUser']['user_login'];
    }

    // add some another variables for user params
    if (!is_null($_SESSION['gift'])) {
        $userDefaultParams['giftValue'] = $_SESSION['gift']['value'];

        $userDefaultParams['giftId'] = $_SESSION['gift']['id'];
        $userDefaultParams['giftName'] = $_SESSION['gift']['name'];

        $userDefaultParams['userChoiceFirst'] = $_SESSION['gift']['userChoiceFirst'];
        $userDefaultParams['userChoiceSecond'] = $_SESSION['gift']['userChoiceSecond'];
    }

    // proto router
    if (!is_null($_SESSION['loggedUser'])) {
        $menu = new Renderer('loggedUser.html');

        if (is_null($_SESSION['raffle']['lastToken']) and is_null($_GET['giftAction']))
            $content = new Renderer('raffle.html');
        elseif (!is_null($_GET['giftAction']))
            $content = new Renderer((new LogoutService('success.html'))->getServiceTemplate());
        else $content = new Renderer('userChoice.html');

//        $content = (is_null($_SESSION['raffle']['lastToken']))
//            ? new Renderer('raffle.html') : new Renderer('userChoice.html');
    } else {
        $menu = new Renderer('authMenu.html');
        $content = new Renderer($authService->getServiceTemplate());
    }

    // app bootstrap
    $indexContent = new Renderer('index.html');

} catch (Exception $e) {
    $e->getMessage();
} finally {
    echo $indexContent->render([
        'menu' => $menu->render(['userLogin' => Helper::mbUcFirst($_SESSION['loggedUser']['user_login'])]),
        'module' => $content->render($userDefaultParams),
        'errors' => $authService->action(),
    ]);
}

var_dump(is_null($_GET['giftAction']));