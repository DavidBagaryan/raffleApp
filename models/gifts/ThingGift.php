<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.04.2018
 * Time: 0:44
 */

namespace app\models\gifts;


use app\models\DataBase;
use app\models\Helper;

class ThingGift extends Gift
{
    const MAX_RANDOM = 30;

    const userChoiceFirst = 'забрать подарок';

    const userChoiceSecond = 'отказаться';

    /**
     * @param $giftValue
     * @return array|null
     */
    static function giftValue($giftValue)
    {
        $query = 'SELECT * FROM things_types WHERE id = ?';
        $giftData = DataBase::getInstance()->prepare($query);
        $giftData->execute([$giftValue]);

        $data = $giftData->fetch();

        if ($data) self::saveGift($data);

        return $data;
    }

    /**
     * @param array $data
     */
    static function saveGift($data)
    {
        $_SESSION['gift']['id'] = $data['id'];
        $_SESSION['gift']['name'] = $data['thing_name'];

        $query = 'SELECT * FROM user_gift WHERE user_id = ? and thing_id = ?';
        $giftData = DataBase::getInstance()->prepare($query);
        $giftData->execute([
            $_SESSION['loggedUser']['id'],
            $data['id']
        ]);

        var_dump($giftData->fetch());
    }

    /**
     * @param array $thing
     * @return string|null
     */
    static function reduceList($thing)
    {
        if ($thing and !Helper::checkUser('final')) {
            $_SESSION['final']['lastToken'] = $_POST['finalGiftToken'];

            $query = 'UPDATE things_types SET things_count = things_count-1 
                      WHERE id = ? AND thing_name = ?';
            $giftData = DataBase::getInstance()->prepare($query);
            $giftData->execute([$thing['id'], $thing['name']]);

            return self::finalAction();
        } else return null;
    }

    /**
     * @return string
     */
    static function finalAction()
    {
        unset($_SESSION['loggedUser'], $_SESSION['gift']);
        header("refresh:4; url=/");

        return "спасибо, что вы с нами!\nдо свидания!";
    }
}