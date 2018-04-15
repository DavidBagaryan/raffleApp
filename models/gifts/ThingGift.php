<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.04.2018
 * Time: 0:44
 */

namespace app\models\gifts;


class ThingGift extends Gift
{
    const MAX_RANDOM = 30;

    static function giftValue($giftValue)
    {
        return "подарок номер {$giftValue}";
    }
}