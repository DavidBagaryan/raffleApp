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
        $this->value = rand(0, static::MAX_RANDOM);
        $_SESSION['gift']['value'] = $this->giftSelector();;
    }

    /**
     * @return string|null
     */
    protected function giftSelector()
    {
        return self::dictionary(static::class, $this->value);
    }

    /**
     * @param $random
     * @return Gift|null
     */
    static function select($random)
    {
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
            default:
                return null;
        }
    }

    private static function dictionary($className, $giftValue)
    {
        switch (preg_replace('~app\\\models\\\gifts\\\~', '', $className)) {
            case 'MoneyGift':
                return MoneyGift::giftValue($giftValue);
                break;
            case 'ThingGift':
                return ThingGift::giftValue($giftValue);
                break;
            case 'BonusGift':
                return BonusGift::giftValue($giftValue);
                break;
            default:
                return null;
        }
    }
}