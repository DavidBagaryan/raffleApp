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

        $_SESSION['gift']['type'] = $this->getType();;
        $_SESSION['gift']['value'] = $this->value;
    }

    /**
     * @return string|null
     */
    protected function getType()
    {
        return self::dictionary(static::class);
    }

    private static function dictionary($className)
    {
        switch (preg_replace('~app\\\models\\\gifts\\\~', '', $className)) {
            case 'MoneyGift':
                return 'Деньги (у.е.)';
                break;
            case 'ThingGift':
                return 'Подарок';
                break;
            case 'BonusGift':
                return 'Бонус в нашем казино';
                break;
            default:
                return null;
        }
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
}