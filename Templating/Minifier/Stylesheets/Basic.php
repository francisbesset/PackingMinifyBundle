<?php

namespace Bundle\PackingMinifyBundle\Templating\Minifier\Stylesheets;

use Bundle\PackingMinifyBundle\Templating\Minifier\MinifierInterface;

class Basic implements MinifierInterface
{
    public function minify($content)
    {
        return preg_replace('/\s\s+/m', '', str_replace(array("\n", "\t"), '', $content));
    }
}