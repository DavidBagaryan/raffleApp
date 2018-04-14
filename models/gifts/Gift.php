<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.04.2018
 * Time: 22:31
 */

namespace app\models\gifts;


abstract class Gift
{
    const MAX_RANDOM = 0;

    /**
     * @var int|null
     */
    private $value = null;

    /**
     * Gift constructor.
     */
    public function __construct()
    {
        var_dump($_POST['giftToken'] !== $_SESSION['lastToken']);
        if ($_POST['giftToken'] !== $_SESSION['lastToken'])
            $this->value = rand(0, static::MAX_RANDOM);
        else $_SESSION['lastToken'] = $_POST['giftToken'];
    }

    /**
     * @param $random
     * @return Gift
     */
    static function select($random)
    {
        var_dump($random);
        switch ($random) {
            case 1:
                return new MoneyGift();
                break;
            case 2:
                return new BonusGift();
                break;
            case 3:
                return new ThingGift();
                break;
        }
    }
}