<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 12.04.2018
 * Time: 15:38
 */

use app\models\gifts\Gift;
use app\models\gifts\MoneyGift;
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
        if ($raffle instanceof ThingGift) {
            $userDefaultParams['giftValue'] = $raffle->getGiftValue()['thing_name'];
            $userDefaultParams['giftId'] = $raffle->getId();
            $userDefaultParams['giftName'] = $raffle->getName();
        } else $userDefaultParams['giftValue'] = $raffle->getGiftValue();
    }

    // proto router
    if (!is_null($_SESSION['loggedUser']) and $_GET['action'] !== 'logout') {
        $menu = new Renderer('loggedUser.html');

        if (!$raffle) $content = new Renderer('raffle.html');
        else {
            $content = new Renderer('userChoice.html');
            switch ($_GET['giftAction']) {
                case 'first':
                    $userDefaultParams['errors'] = $raffle->userFirstAction($_SESSION['loggedUser']);
                    $userDefaultParams['userChoiceFirst'] = null;
                    $userDefaultParams['userChoiceSecond'] = null;
                    break;
                case 'second':
                    $secondAction = $raffle->userSecondAction($_SESSION['loggedUser']);

                    if ($raffle instanceof MoneyGift) {
                        $userDefaultParams['errors'] = $secondAction['error'];
                        $userDefaultParams['userBonus'] = $secondAction['newBonus']
                            ? $secondAction['newBonus']
                            : $_SESSION['loggedUser']['bonuses'];
                    } else $userDefaultParams['errors'] = $secondAction;

                    $userDefaultParams['userChoiceFirst'] = null;
                    $userDefaultParams['userChoiceSecond'] = null;
                    break;
                default:
                    $userDefaultParams['userChoiceFirst'] = $raffle::USER_CHOICE_FIRST;
                    $userDefaultParams['userChoiceSecond'] = $raffle::USER_CHOICE_SECOND;
                    break;
            }
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

//var_dump($_SESSION);
