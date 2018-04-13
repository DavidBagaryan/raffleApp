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
    function auth()
    {
        $getUserData = $this->getUserData();

        if (self::$login === '') self::$errors[] = 'введите логин';
        if (self::$password === '') self::$errors[] = 'введите пароль';
        if (self::$password2 !== self::$password) self::$errors[] = 'пароли не совпадают';
        if ($getUserData['loginMatches'] > 0) self::$errors[] = 'пользователь с таким именем существует';
        if (empty(self::$errors)) $this->addUser();
        //else return array_shift(self::$errors);

        return array_shift(self::$errors);
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