<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 13.04.2018
 * Time: 22:02
 */

namespace app\models\authServices;


class NoService extends AuthService
{
    function action()
    {
        return null;
    }
}