<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
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
            foreach ($params as $param => $value) {
                if (!is_null($value)) $this->replaceValue($param, $value);
                else $this->replaceTag($param);
            }

        return $this->template;
    }

    private function replaceValue($param, $value)
    {
        $this->template = str_replace("~{$param}~", $value, $this->template);
    }

    private function replaceTag($param)
    {
        $fullTagPattern = function ($innerHtml) {
            $tag = '(\<(\/?[^>]+)>)';
            return "/{$tag}~{$innerHtml}~{$tag}/";
        };

        $this->template = preg_replace($fullTagPattern($param), '', $this->template);
    }
}