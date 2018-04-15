<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.04.2018
 * Time: 22:13
 */

namespace app\models;


use app\models\gifts\Gift;

class Raffle
{
    /**
     * @return Gift|string|null
     * @throws \Exception
     */
    static function check()
    {
        $random = intval($_POST['randomGift']);

        if (Helper::checkUser('raffle')) return null;
        else {
            $_SESSION['raffle']['lastToken'] = $_POST['raffleGiftToken'];
            if (self::checkRandom($random) and $_POST['action'] === 'raffle')
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
}