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

        if (self::checkRandom($random) and AuthService::$postData['action'] === 'raffle')
            return Gift::select($random);
        return null;
    }

    /**
     * @param int $random
     * @return bool
     */
    private static function checkRandom($random)
    {
        return (is_numeric($random) and $random >= 1 and $random <= 3);
    }
}