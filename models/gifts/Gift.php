<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 14.04.2018
 * Time: 22:31
 */

namespace app\models\gifts;


use app\models\Helper;
use Exception;

abstract class Gift
{
    const MAX_RANDOM = 0;

    const USER_CHOICE_FIRST = null;

    const USER_CHOICE_SECOND = null;

    /**
     * @var int
     */
    protected $value = null;

    /**
     * @var string
     */
    protected $giftValue = null;

    /**
     * @param $user
     * @return null|string
     */
    abstract public function userFirstAction($user);

    /**
     * @param array|null $user
     * @return array|string|null
     */
    abstract public function userSecondAction($user = null);

    /**
     * Gift constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->value = rand(0, static::MAX_RANDOM);
        $this->giftValue = static::setGiftValue($this->value);
    }

    /**
     * @return array|string
     */
    public function getGiftValue()
    {
        return $this->giftValue;
    }

    /**
     * @param int|null $value
     * @return array|string|null
     */
    protected static function setGiftValue($value)
    {
        return $value;
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
     * @return string
     */
    static function endAction()
    {
        unset($_SESSION['loggedUser'], $_SESSION['gift']);
        header("refresh:5; url=/");

        return "спасибо, что вы с нами!\nдо свидания!";
    }
}