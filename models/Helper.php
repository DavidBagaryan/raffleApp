<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 14.04.2018
 * Time: 21:36
 */

namespace app\models;


class Helper
{
    /**
     * @param $str
     * @param string $encoding
     * @return false|string
     */
    static function mbUcFirst($str, $encoding = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
            mb_substr($str, 1, mb_strlen($str), $encoding);
    }

    /**
     * @param string $formName
     * @return bool
     */
    public static function checkUser($formName)
    {
        return ($_POST[$formName . 'GiftToken'] === $_SESSION[$formName]['lastToken']
            or ($_POST['userId'] !== $_SESSION['loggedUser']['id']
                and $_POST['userLogin'] !== $_SESSION['loggedUser']['user_login']));
    }
}