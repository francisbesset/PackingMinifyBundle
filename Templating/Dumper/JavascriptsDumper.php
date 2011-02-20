<?php

namespace BeSimple\PackingMinifyBundle\Templating\Dumper;

class JavascriptsDumper
{
    protected $javascripts;
    protected $options;
    protected $currentFile;

    public function __construct(array $javascripts, array $options)
    {
        $this->javascripts = $javascripts;

        $this->options = array(
            'web_dir'           => null,
            'script_name_uri'   => null,
        );

        // check option names
        if ($diff = array_diff(array_keys($options), array_keys($this->options))) {
            throw new \InvalidArgumentException(sprintf('The JavascriptsDumper does not support the following options: \'%s\'.', implode('\', \'', $diff)));
        }

        $this->options = array_merge($this->options, $options);
    }

    public function dump()
    {
        $content = '';

        foreach ($this->javascripts as $javascript) {
            $this->currentFile = $javascript;

            if (!is_file($this->getRequiredOption('web_dir').$this->currentFile)) {
                throw new \InvalidArgumentException(sprintf('The file "%s" does not exist', $this->getRequiredOption('web_dir').$this->currentFile));
            }

            $content .= file_get_contents($this->getRequiredOption('web_dir').$this->currentFile)."\n";
        }

        return $content;
        //return $this->minify($content);
    }

    protected function getRequiredOption($option)
    {
        if (null === $this->options[$option]) {
            throw new \InvalidArgumentException(sprintf('The option \'%s\' is required.', $option));
        }

        return $this->options[$option];
    }
}