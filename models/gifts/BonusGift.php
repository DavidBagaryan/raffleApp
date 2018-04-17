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

    const USER_CHOICE_FIRST = 'зачислить баллы';

    public function userFirstAction($user)
    {
        self::addBonus($this, $user, true);
    }

    public function userSecondAction($user = null)
    {
        return $user;
    }

    static function setGiftValue($bonus)
    {
        return "бонус в нашем казино в размере {$bonus} баллов";
    }
}