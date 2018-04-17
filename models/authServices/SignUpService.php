<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 13.04.2018
 * Time: 16:21
 */

namespace app\models\authServices;


use app\models\DataBase;

class SignUpService extends AuthService
{
    const PASSWORD_VERIFICATION_ERROR = 'пароли не совпадают';

    const USER_LOGIN_MATCH_ERROR = 'пользователь с таким именем существует';

    const EMPTY_ADDRESS = 'поле адреса пусто';

    const EMPTY_BANK_DETAILS = 'поле банковских реквизитов пусто';

    function action()
    {
        $userData = $this->getUserData();
        $this->checkLogPass();
        $this->checkAddressAndBank();

        self::checkLength([
            'логин' => self::$login,
            'пароль' => self::$password,
            'адрес' => self::$postData['address'],
            'банковские реквизиты' => self::$postData['bankDetails']
        ]);

        if (self::$password2 !== self::$password) self::$errors[] = self::PASSWORD_VERIFICATION_ERROR;
        if ($userData['loginMatches'] > 0) self::$errors[] = self::USER_LOGIN_MATCH_ERROR;

        if (empty(self::$errors)) $this->addUser();

        return array_shift(self::$errors);
    }

    /**
     * DavidBagaryan:
     * this method just a joke
     * like validator simulator
     */
    private function checkAddressAndBank()
    {
        if (self::$postData['address'] === '') self::$errors[] = self::EMPTY_ADDRESS;
        if (self::$postData['bankDetails'] === '') self::$errors[] = self::EMPTY_BANK_DETAILS;
    }

    private function addUser()
    {
        $query = 'INSERT INTO raffle_users (`user_login`, `user_password`, `address`, `bank_account`) 
                  VALUES (?, ?, ?, ?)';

        if (DataBase::getInstance()->prepare($query)
            ->execute([
                self::$login,
                password_hash(self::$password, PASSWORD_DEFAULT),
                self::$postData['address'],
                self::$postData['bankDetails'],
            ])) {
            self::$errors[] = 'регистрация прошла успешно';
            header("refresh:1; url=/");
        } else self::$errors[] = "ошибка при регистрации!\nпроверьте данные и повторите попытку";
    }
}