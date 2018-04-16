<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 15.04.2018
 * Time: 0:44
 */

namespace app\models\gifts;


class BonusGift extends Gift
{
    const MAX_RANDOM = 9999;

    const userChoiceFirst = null;

    const userChoiceSecond = null;

    static function giftValue($bonus)
    {
        return "бонус в нашем казино в размере {$bonus} баллов";
    }
}