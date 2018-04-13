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
        unset($_SESSION['loggedUser']);
        session_destroy();
        header("refresh:1; url=/");
        return null;
    }
}