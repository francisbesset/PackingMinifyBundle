<?php

namespace BeSimple\PackingMinifyBundle\Templating\Dumper;

class StylesheetsDumper
{
    protected $stylesheets;
    protected $options;
    protected $currentFile;

    public function __construct(array $stylesheets, array $options)
    {
        $this->stylesheets = $stylesheets;

        $this->options = array(
            'web_dir'           => null,
            'script_name_uri'   => null,
        );

        // check option names
        if ($diff = array_diff(array_keys($options), array_keys($this->options))) {
            throw new \InvalidArgumentException(sprintf('The StylesheetsDumper does not support the following options: \'%s\'.', implode('\', \'', $diff)));
        }

        $this->options = array_merge($this->options, $options);
    }

    public function dump()
    {
        $content = '';

        foreach ($this->stylesheets as $stylesheet) {
            $this->currentFile = $stylesheet;

            if (!is_file($this->getRequiredOption('web_dir').$this->currentFile)) {
                throw new \InvalidArgumentException(sprintf('The file "%s" does not exist', $this->getRequiredOption('web_dir').$this->currentFile));
            }

            $content .= $this->replaceUrl(
                file_get_contents($this->getRequiredOption('web_dir').$this->currentFile)
            )."\n";
        }

        return $content;
    }

    protected function replaceUrl($content)
    {
        return preg_replace_callback('/url\((?:\'|")?([^\'"\/][^\'"]+)(?:\'|")?\)/iU', array($this, 'updateUrl'), $content);
    }

    protected function updateUrl($url)
    {
        if ($this->getRequiredOption('script_name_uri')) {
            if (preg_match('#^\.\.#', $url[1])) {
                return 'url(../'.$url[1].')';
            } else {
                return 'url(../../'.dirname($this->currentFile).'/'.$url[1].')';
            }
        } else {
            if (preg_match('#^\.\.#', $url[1])) {
                return $url[0];
            } else {
                return 'url(../'.dirname($this->currentFile).'/'.$url[1].')';
            }
        }
    }

    protected function getRequiredOption($option)
    {
        if (null === $this->options[$option]) {
            throw new \InvalidArgumentException(sprintf('The option \'%s\' is required.', $option));
        }

        return $this->options[$option];
    }
}