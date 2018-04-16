<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 15.04.2018
 * Time: 0:43
 */

namespace app\models\gifts;


class MoneyGift extends Gift
{
    const MAX_RANDOM = 1000;

    const userChoiceFirst = 'отправить на счет';

    const userChoiceSecond = 'конвертировать в баллы';

    static function giftValue($sum)
    {
        return "денежная сумма в размере {$sum} условных единиц";
    }
}