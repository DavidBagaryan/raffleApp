<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.04.2018
 * Time: 16:05
 */

namespace app\models\authServices;


use app\models\DataBase;
use Exception;

abstract class AuthService
{
    /**
     * @var string
     */
    protected $serviceTemplate = null;

    /**
     * @var array
     */
    static $postData = [];

    /**
     * @var array
     */
    static $getData = [];

    /**
     * @return string
     */
    public function getServiceTemplate()
    {
        return $this->serviceTemplate;
    }

    /**
     * @var array
     */
    static $errors = [];

    /**
     * @var string
     */
    static $login = null;

    /**
     * @var string
     */
    static $password = null;

    /**
     * @var string
     */
    static $password2 = null;

    /**
     * AuthService constructor.
     * @param string|null $serviceTemplate
     */
    public function __construct($serviceTemplate = null)
    {
        $this->serviceTemplate = $serviceTemplate;
    }

    /**
     * @return AuthService
     * @throws \Exception
     */
    static function check()
    {
        self::$postData = $_POST;
        self::$getData = $_GET;

        self::$login = trim(self::$postData['login']);
        self::$password = self::$postData['pass'];
        self::$password2 = self::$postData['pass2'];

        if (isset(self::$getData['action'])){
            if (self::$getData['action'] === 'signUp') return new SignUpService('signUp.html');
            if (self::$getData['action'] === 'login') return new LoginService('login.html');
            else throw new Exception('underfind action for ' . __METHOD__);
        } else return new NoService();
    }

    /**
     * @return string|null
     */
    abstract function auth();

    protected function getUserData()
    {
        $query = 'SELECT * FROM raffle_users WHERE user_login = ?';
        $user = DataBase::getInstance()->prepare($query);
        $user->execute([self::$login]);

        return [
            'loginMatches' => $user->rowCount(),
            'user' => $user->fetch()
        ];
    }

    static function logout()
    {
        unset($_SESSION['logged_user']);
        session_destroy();

        header("refresh:1; url=/");
    }
}