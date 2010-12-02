<?php

namespace Bundle\PackingMinifyBundle\Templating\Minifier;

abstract class Minifier implements MinifierInterface
{
    protected $options;
    protected $vendorFolder;

    public function __construct()
    {
        $this->vendorFolder = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor');
    }
}
