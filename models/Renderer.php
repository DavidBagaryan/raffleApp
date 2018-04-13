<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.04.2018
 * Time: 13:35
 */

namespace app\models;


class Renderer
{
    const VIEW = 'views';

    /**
     * @var string
     */
    private $template;

    /**
     * Renderer constructor.
     * @param $templateName
     */
    public function __construct($templateName)
    {
        $this->template = file_get_contents(self::VIEW . DIRECTORY_SEPARATOR . $templateName);
    }

    /**
     * @param $params
     * @return bool|string
     */
    function render($params)
    {
        if (is_array($params))
            foreach ($params as $param => $value)
                $this->template = str_replace("~{$param}~", $value, $this->template);


        return $this->template ? $this->template : null;
    }
}