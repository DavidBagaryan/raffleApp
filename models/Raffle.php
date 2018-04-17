<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 14.04.2018
 * Time: 22:13
 */

namespace app\models;


use app\models\gifts\Gift;

class Raffle
{
    /**
     * @return Gift
     * @throws \Exception
     */
    static function check()
    {
        $random = intval($_POST['randomGift']);

        if (!Helper::checkUser('raffle')) {
            $_SESSION['raffle']['lastToken'] = $_POST['raffleGiftToken'];
            if (self::checkRandom($random) and $_POST['action'] === 'raffle')
                $_SESSION['gift'] = serialize(Gift::select($random));
        }

        return unserialize($_SESSION['gift']);
    }

    /**
     * @param int $random
     * @return bool
     */
    private
    static function checkRandom($random)
    {
        return (is_numeric($random) and $random >= 1 and $random <= 3);
    }
}