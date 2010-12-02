<?php

namespace Bundle\PackingMinifyBundle\Templating\Minifier\Stylesheets;

use Bundle\PackingMinifyBundle\Templating\Minifier\MinifierInterface;

class CSSMin implements MinifierInterface
{
    protected $options;

    /**
     * Constructor.
     *
     * @param Array $options Options
     */
    public function __construct(array $options = array())
    {
        $this->options = array(
            'remove-empty-blocks'     => true,
            'remove-empty-rulesets'   => true,
            'remove-last-semicolons'  => true,
            'convert-css3-properties' => false,
            'convert-color-values'    => false,
            'compress-color-values'   => false,
            'compress-unit-values'    => false,
            'emulate-css3-variables'  => true,
        );

        // check option names
        if ($diff = array_diff(array_keys($options), array_keys($this->options))) {
            throw new \InvalidArgumentException(sprintf('Packer      does not support the following options: \'%s\'.', implode('\', \'', $diff)));
        }

        $this->options = array_merge($this->options, $options);
    }

    public function minify($content)
    {
        require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'cssmin'.DIRECTORY_SEPARATOR.'cssmin.php';

        return \CSSMin::minify($content, $this->options);
    }
}
