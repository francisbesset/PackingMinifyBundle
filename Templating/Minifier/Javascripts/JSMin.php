<?php

namespace Bundle\PackingMinifyBundle\Templating\Minifier\Javascripts;

use Bundle\PackingMinifyBundle\Templating\Minifier\MinifierInterface;

class JSMin implements MinifierInterface
{
    public function minify($content)
    {
        require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'jsmin-php'.DIRECTORY_SEPARATOR.'jsmin.php';

        return \JSMin::minify($content);
    }
}