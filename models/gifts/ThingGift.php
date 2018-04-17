<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 15.04.2018
 * Time: 0:44
 */

namespace app\models\gifts;


use app\models\DataBase;
use app\models\Helper;

class ThingGift extends Gift
{
    const THING_TYPE_ID = 3;

    const MAX_RANDOM = 30;

    const USER_CHOICE_FIRST = 'забрать подарок';

    const USER_CHOICE_SECOND = 'отказаться';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * ThingGift constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->id = intval($this->giftValue['id']);
        $this->name = $this->giftValue['thing_name'];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function userFirstAction($user)
    {
        if (!Helper::checkUser('final')) {
            $_SESSION['final']['lastToken'] = $_POST['finalGiftToken'];

            $query = 'UPDATE things_types SET things_count = things_count-1 
                      WHERE id = ? AND thing_name = ?';

            if (DataBase::getInstance()->prepare($query)->execute([$this->id, $this->name])
                and $this->saveGift($user)) return $this->finalAction($user);
            else return 'ошибка при обновлении данных по подарку в ' . __METHOD__;
        } else return null;
    }

    public function userSecondAction($user = null)
    {
        return self::endAction();
    }

    /**
     * @param array $user
     * @return string
     */
    protected function finalAction($user)
    {
        unset($_SESSION['loggedUser'], $_SESSION['gift']);
        header("refresh:4; url=/");

        return "подарок будет выслан Вам по адресу {$user['address']}.\nСпасибо, что вы с нами!\nдо свидания!";
    }

    /**
     * @param array $user
     * @return bool
     */
    protected function saveGift($user)
    {
        $query = 'INSERT INTO user_gift (`user_id`, `gift`, `gift_type`, `date_insert`) VALUES (?, ?, ?, NOW())';
        return DataBase::getInstance()->prepare($query)->execute([
            $user['id'],
            $this->id,
            self::THING_TYPE_ID
        ]);
    }

    protected static function setGiftValue($value)
    {
        $query = 'SELECT * FROM things_types WHERE id = ?';
        $giftData = DataBase::getInstance()->prepare($query);
        $giftData->execute([$value]);

        $data = $giftData->fetch();

        return $data;
    }
}