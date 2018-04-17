<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 15.04.2018
 * Time: 0:43
 */

namespace app\models\gifts;


use app\models\DataBase;
use app\models\Helper;

class MoneyGift extends Gift
{
    const MAX_RANDOM = 1000;

    const MONEY_TYPE_ID = 1;

    const USER_CHOICE_FIRST = 'отправить на счет';

    const USER_CHOICE_SECOND = 'конвертировать в баллы';

    static $conversionRate = 0.15;

    static function setGiftValue($sum)
    {
        return "денежная сумма в размере {$sum} у.е.";
    }

    public function userFirstAction($user)
    {
        if (!Helper::checkUser('final')) {
            $_SESSION['final']['lastToken'] = $_POST['finalGiftToken'];

            //TODO query to reduce casino bank money

            $query = 'INSERT INTO user_gift (`user_id`, `gift`, `gift_type`, `date_insert`) VALUES (?, ?, ?, ?)';
            if (DataBase::getInstance()->prepare($query)->execute([
                $user['id'],
                $this->value,
                self::MONEY_TYPE_ID,
                'NOW()'
            ])) {
                self::endAction();
                return "денежная сумма будет перечислена вам на счет в банке\nреквизиты: {$user['bank_account']}";
            } else return 'ошибка при перчислении денежных средств';
        } else return null;
    }

    public function userSecondAction($user = null)
    {
        if (!Helper::checkUser('final')) {
            $_SESSION['final']['lastToken'] = $_POST['finalGiftToken'];
            $bonus = intval($this->value / self::$conversionRate);

            $query = 'UPDATE raffle_users SET `bonuses` = `bonuses` + ? WHERE `id` = ? AND `user_login` = ?';
            if (DataBase::getInstance()->prepare($query)->execute([
                $bonus,
                $user['id'],
                $user['user_login']
            ])) {
                $_SESSION['loggedUser']['bonuses'] = $_SESSION['loggedUser']['bonuses'] + $bonus;
                return [
                    'error' => "денежная сумма будет конвертированна в баллы лояльности в нашем казино,\n
                        количество баллов: {$bonus}",
                    'newBonus' => $_SESSION['loggedUser']['bonuses']
                ];
            } else return 'ошибка конвертации денежных средств';
        } else return null;
    }
}