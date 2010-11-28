<?php

namespace Bundle\PackingMinifyBundle;

class PackingMinify
{
    protected $cacheDir;

    public function __construct($cacheDir)
    {
        $this->setCacheDir($cacheDir);
    }

    public function get($file, $format)
    {
        if (is_file($this->cacheDir.DIRECTORY_SEPARATOR.$format.DIRECTORY_SEPARATOR.$file.'.'.$format)) {
            return file_get_contents(
                $this->cacheDir.DIRECTORY_SEPARATOR.$format.DIRECTORY_SEPARATOR.$file.'.'.$format
            );
        }

        return false;
    }

    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }
}