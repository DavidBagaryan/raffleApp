<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.04.2018
 * Time: 22:13
 */

namespace app\models;


use app\models\authServices\AuthService;
use app\models\gifts\Gift;

class Raffle
{
    /**
     * @return Gift|null
     */
    static function check()
    {
        $random = intval(AuthService::$postData['randomGift']);

        if (self::checkUser()) return null;
        else {
            $_SESSION['gift']['lastToken'] = AuthService::$postData['giftToken'];
            if (self::checkRandom($random) and AuthService::$postData['action'] === 'raffle')
                return Gift::select($random);
            else return null;
        }
    }

    /**
     * @param int $random
     * @return bool
     */
    private static function checkRandom($random)
    {
        return (is_numeric($random) and $random >= 1 and $random <= 3);
    }

    /**
     * @return bool
     */
    private static function checkUser()
    {
        return (AuthService::$postData['giftToken'] === $_SESSION['gift']['lastToken']
            or (AuthService::$postData['userId'] !== $_SESSION['loggedUser']['id']
                and AuthService::$postData['userLogin'] !== $_SESSION['loggedUser']['user_login']));
    }
}