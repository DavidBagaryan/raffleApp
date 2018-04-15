<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.04.2018
 * Time: 0:44
 */

namespace app\models\gifts;


use app\models\DataBase;

class ThingGift extends Gift
{
    const MAX_RANDOM = 30;

    const userChoiceFirst = 'забрать подарок';

    const userChoiceSecond = 'отказаться';

    public function getFun()
    {
        return 'lol';
    }

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

        $_SESSION['gift']['id'] = $data['id'];
        $_SESSION['gift']['name'] = $data['thing_name'];

        return $data;
    }

    /**
     * @param array $thing
     */
    static function reduceList($thing)
    {
        if ($thing) {
            $query = 'UPDATE things_types SET things_count = things_count-1 
                      WHERE id = ? AND thing_name = ?';
            $giftData = DataBase::getInstance()->prepare($query);
            $giftData->execute([$thing['id'], $thing['thing_name']]);
        }
    }
}