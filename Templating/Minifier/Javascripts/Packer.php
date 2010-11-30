<?php

namespace Bundle\PackingMinifyBundle\Templating\Minifier\Javascripts;

use Bundle\PackingMinifyBundle\Templating\Minifier\MinifierInterface;

class Packer implements MinifierInterface
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
            'encoding'      => 'Normal',
            'fast_decode'   => true,
            'special_chars' => false,
        );
        
        // check option names
        if ($diff = array_diff(array_keys($options), array_keys($this->options))) {
            throw new \InvalidArgumentException(sprintf('Packer      does not support the following options: \'%s\'.', implode('\', \'', $diff)));
        }

        $this->options = array_merge($this->options, $options);
    }

    public function minify($content)
    {
        require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'packer'.DIRECTORY_SEPARATOR.'class.JavaScriptPacker.php';

        $packer = new \JavaScriptPacker($content, $this->options['encoding'], $this->options['fast_decode'], $this->options['special_chars']);

        return $packer->pack();
    }
}