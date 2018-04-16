<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.04.2018
 * Time: 15:38
 */

use app\models\gifts\Gift;
use app\models\gifts\ThingGift;
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
        $userDefaultParams['userAddress'] = $_SESSION['loggedUser']['address'];
        $userDefaultParams['userBank'] = $_SESSION['loggedUser']['bank_account'];
        $userDefaultParams['userBonus'] = $_SESSION['loggedUser']['bonuses'];
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

        if (is_null($_SESSION['gift'])) $content = new Renderer('raffle.html');
        if (!is_null($_SESSION['gift']) and is_null($_GET['giftAction']))
            $content = new Renderer('userChoice.html');
        if (!is_null($_SESSION['gift']) and $_GET['giftAction'] === 'first') {
            $content = new Renderer('success.html');
            $userDefaultParams['errors'] = ThingGift::reduceList($_SESSION['gift']);
        } elseif (!is_null($_SESSION['gift']) and $_GET['giftAction'] === 'second') {
            $content = new Renderer('denial.html');
            $userDefaultParams['errors'] = ThingGift::finalAction();
        }

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
        'menu' => $menu->render($userDefaultParams),
        'module' => $content->render($userDefaultParams),
        'errors' => $userDefaultParams['errors'] ? $userDefaultParams['errors'] : $authService->action(),
    ]);
}

var_dump($_POST);
