<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 13.04.2018
 * Time: 16:22
 */

namespace app\models\authServices;


class LoginService extends AuthService
{
    const PASSWORD_ERROR = 'неправильно введен пароль';

    const USER_DISABLE = 'аккаунт этого пользователя неактивен';

    const USER_LOGIN_NO_MATCH = 'пользователь с таким логином не найден';

    function action()
    {
        $userData = $this->getUserData();
        $this->checkLogPass();

        self::checkLength([
            'логин' => self::$login,
            'пароль' => self::$password
        ]);

        if ($userData['loginMatches'] > 0)
            if (password_verify(self::$password, $userData['user']['user_password']))
                if ($userData['user']['is_active'] === 'Y') $this->enterUserParams($userData['user']);
                else self::$errors[] = self::USER_DISABLE;
            else self::$errors[] = self::PASSWORD_ERROR;
        else self::$errors[] = self::USER_LOGIN_NO_MATCH;

        return array_shift(self::$errors);
    }

    /**
     * @param array $user
     */
    private function enterUserParams($user)
    {
        $_SESSION['loggedUser'] = $user;
        self::$errors[] = 'вход в аккаунт';
        header("refresh:1; url=/");
    }
}