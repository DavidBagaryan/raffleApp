<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
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

//unset($_SESSION['final']['lastToken'], $_POST, $_GET);die;

try {
    $authService = AuthService::check();
    $raffle = Raffle::check();
    Gift::check();

    //add some variables for user params
    if (!is_null($_SESSION['loggedUser'])) {
        $userDefaultParams['id'] = $_SESSION['loggedUser']['id'];
        $userDefaultParams['userLogin'] = $_SESSION['loggedUser']['user_login'];
        $userDefaultParams['userAddress'] = $_SESSION['loggedUser']['address'];
        $userDefaultParams['userBank'] = $_SESSION['loggedUser']['bank_account'];
        $userDefaultParams['userBonus'] = $_SESSION['loggedUser']['bonuses'];
    }

    // add some another variables for user params
    if ($raffle and $raffle instanceof Gift) {
        $userDefaultParams['giftValue'] = $raffle->getGiftValue()['thing_name'];

        $userDefaultParams['giftId'] = ($raffle instanceof ThingGift) ? $raffle->getId() : null;
        $userDefaultParams['giftName'] = ($raffle instanceof ThingGift) ? $raffle->getName() : null;

        $userDefaultParams['userChoiceFirst'] = $raffle::userChoiceFirst;
        $userDefaultParams['userChoiceSecond'] = $raffle::userChoiceSecond;
    }

    // proto router
    if (!is_null($_SESSION['loggedUser'])) {
        $menu = new Renderer('loggedUser.html');

        if (!$raffle) $content = new Renderer('raffle.html');
        else {
            if ($_GET['giftAction'] === 'first') {
                $content = new Renderer('success.html');
                $userDefaultParams['errors'] = $raffle->reduceList($_SESSION['loggedUser']);
            } elseif ($_GET['giftAction'] === 'second') {
                $content = new Renderer('denial.html');
                $userDefaultParams['errors'] = ThingGift::finalAction();
            } else $content = new Renderer('userChoice.html');
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

var_dump($_SESSION);
