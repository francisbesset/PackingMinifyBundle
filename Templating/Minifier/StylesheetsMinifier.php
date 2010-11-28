<?php

namespace Bundle\DynamicsBundle\Templating\Minifier;

class StylesheetsMinifier
{
    public static function minify($content)
    {
        return preg_replace('/\s\s+/m', '', str_replace(array("\n", "\t"), '', $content));
    }
}