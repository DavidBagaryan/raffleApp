<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.04.2018
 * Time: 16:22
 */

namespace app\models\authServices;


class LoginService extends AuthService
{
    function auth()
    {
        $getUserData = $this->getUserData();
        if (self::$login === '') self::$errors[] = 'введите логин';
        if ($getUserData['loginMatches'] > 0)
            if (password_verify(self::$password, $getUserData['user']['user_password']))
                $this->enterUserParams($getUserData['user']);
            else self::$errors[] = 'неправильно введен пароль';
        else self::$errors[] = 'пользователь с таким логином не найден';
        return array_shift(self::$errors);
    }

    function enterUserParams($user)
    {
        $_SESSION['logged_user'] = $user;
        self::$errors[] = 'вход в аккаунт';
        header("refresh:1; url=/");
    }
}