<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.04.2018
 * Time: 16:21
 */

namespace app\models\authServices;


use app\models\DataBase;

class SignUpService extends AuthService
{
    const PASSWORD_VERIFICATION_ERROR = 'пароли не совпадают';

    const USER_LOGIN_MATCH_ERROR = 'пользователь с таким именем существует';

    function action()
    {
        $getUserData = $this->getUserData();

        $this->checkLogPass();
        $this->checkInputLength();
        $this->checkSecondPassLength();

        if (self::$password2 !== self::$password) self::$errors[] = self::PASSWORD_VERIFICATION_ERROR;
        if ($getUserData['loginMatches'] > 0) self::$errors[] = self::USER_LOGIN_MATCH_ERROR;

        if (empty(self::$errors)) $this->addUser();

        return array_shift(self::$errors);
    }

    protected function checkSecondPassLength()
    {
        $long = (strlen(self::$password2) > 200);
        $short = (strlen(self::$password2) < 5);

        if ($long) self::$errors[] = self::TOO_LONG;
        if ($short) self::$errors[] = self::TOO_SHORT;
    }

    private function addUser()
    {
        $query = 'INSERT INTO raffle_users (`user_login`, `user_password`) VALUES (?, ?)';

        DataBase::getInstance()->prepare($query)
            ->execute([self::$login, password_hash(self::$password, PASSWORD_DEFAULT)]);
        self::$errors[] = 'регистрация прошла успешно';

        header("refresh:1; url=/");
    }
}