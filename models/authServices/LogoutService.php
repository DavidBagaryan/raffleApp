<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.04.2018
 * Time: 0:14
 */

namespace app\models\authServices;


class LogoutService extends AuthService
{
    function action()
    {
        if (isset($_SESSION['loggedUser'])) {
            unset($_SESSION);
            session_destroy();
            header("refresh:1; url=/");
            self::$errors[] = 'До свидания!';
        }
        return array_shift(self::$errors);
    }
}