<?php

namespace Bundle\PackingMinifyBundle\Templating\Minifier\Javascripts;

use Bundle\PackingMinifyBundle\Templating\Minifier\Minifier;

class JSMin extends Minifier
{
    public function minify($content)
    {
        require_once $this->vendorFolder.DIRECTORY_SEPARATOR.'jsmin-php'.DIRECTORY_SEPARATOR.'jsmin.php';

        return \JSMin::minify($content);
    }
}
