<?php

namespace BeSimple\PackingMinifyBundle\Templating\Minifier\Stylesheets;

use BeSimple\PackingMinifyBundle\Templating\Minifier\Minifier;

class Basic extends Minifier
{
    public function minify($content)
    {
        return preg_replace('/\s\s+/m', '', str_replace(array("\n", "\t"), '', $content));
    }
}
