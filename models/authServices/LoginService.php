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
    const PASSWORD_ERROR = 'неправильно введен пароль';

    const USER_LOGIN_NO_MATCH = 'пользователь с таким логином не найден';

    function action()
    {
        $getUserData = $this->getUserData();

        $this->checkLogPass();
        $this->checkInputLength();

        if ($getUserData['loginMatches'] > 0)
            if (password_verify(self::$password, $getUserData['user']['user_password']))
                $this->enterUserParams($getUserData['user']);
            else self::$errors[] = self::PASSWORD_ERROR;
        else self::$errors[] = self::USER_LOGIN_NO_MATCH;

        return array_shift(self::$errors);
    }

    function enterUserParams($user)
    {
        $_SESSION['loggedUser'] = $user;
        self::$errors[] = 'вход в аккаунт';
        header("refresh:1; url=/");
    }
}