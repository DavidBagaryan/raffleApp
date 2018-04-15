<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.04.2018
 * Time: 22:31
 */

namespace app\models\gifts;


use app\models\Helper;
use Exception;

abstract class Gift
{
    const MAX_RANDOM = 0;

    const userChoiceFirst = null;

    const userChoiceSecond = null;

    /**
     * @var int|null
     */
    private $value = null;

    /**
     * Gift constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->value = rand(0, static::MAX_RANDOM);
        $_SESSION['gift']['value'] = $this->giftSelector();;
        $_SESSION['gift']['userChoiceFirst'] = static::userChoiceFirst;
        $_SESSION['gift']['userChoiceSecond'] = static::userChoiceSecond;
    }

    /**
     * @return null|string
     * @throws \Exception
     */
    protected function giftSelector()
    {
        return self::dictionary(static::class, $this->value);
    }

    /**
     * @return false|string
     */
    static function check()
    {
        if (Helper::checkUser('chose')) return false;
        else {
            $_SESSION['chose']['lastToken'] = $_POST['choseGiftToken'];
            if (isset($_GET['giftAction']) and $_GET['giftAction'] === 'first')
                return 'приз будет выслан вам по почте!';
            else return false;
        }
    }

    /**
     * @param $random
     * @return Gift|null
     * @throws \Exception
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

    /**
     * @param string $className
     * @param int $giftValue
     * @return null|string
     * @throws Exception
     */
    private static function dictionary($className, $giftValue)
    {
        switch (preg_replace('~app\\\models\\\gifts\\\~', '', $className)) {
            case 'MoneyGift':
                return MoneyGift::giftValue($giftValue);
                break;
            case 'ThingGift':
                return ThingGift::giftValue($giftValue)['thing_name'];
                break;
            case 'BonusGift':
                return BonusGift::giftValue($giftValue);
                break;
            default:
                return null;
        }
    }
}