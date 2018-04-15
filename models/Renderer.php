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
     * @param string $templateName
     */
    public function __construct($templateName)
    {
        $this->template = !is_null($templateName)
            ? file_get_contents(self::VIEW . DIRECTORY_SEPARATOR . $templateName)
            : null;
    }

    /**
     * @param $params
     * @return string|null
     */
    function render($params = null)
    {
        if (!is_null($params) and is_array($params))
            foreach ($params as $param => $value)
                $this->template = str_replace("~{$param}~", $value ? $value : null, $this->template);

        return $this->template;
    }
}