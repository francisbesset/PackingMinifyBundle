<?php

namespace Bundle\PackingMinifyBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\StylesheetsHelper as BaseStylesheetsHelper;
use Bundle\PackingMinifyBundle\Templating\Minifier\MinifierInterface;
use Bundle\PackingMinifyBundle\Templating\Resource\FileResource;

class StylesheetsHelper extends BaseStylesheetsHelper
{
    protected $minifier;
    protected $options;
    protected $resources = array();

    /**
     * Constructor.
     *
     * @param AssetsHelper      $assetHelper A AssetsHelper instance
     * @param MinifierInterface $minifier    A MinifierInterface instance
     * @param Array             $options     Options
     */
    public function __construct(AssetsHelper $assetHelper, MinifierInterface $minifier, array $options = array())
    {
        parent::__construct($assetHelper);

        $this->options = array(
            'cache_dir'      => null,
            'debug'          => false,
            'minify'         => true,
            'dumper_class'   => 'Bundle\\PackingMinifyBundle\\Templating\\Dumper\\StylesheetsDumper',
        );

        // check option names
        if ($diff = array_diff(array_keys($options), array_keys($this->options))) {
            throw new \InvalidArgumentException(sprintf('The StylesheetsHelper does not support the following options: \'%s\'.', implode('\', \'', $diff)));
        }

        $this->options = array_merge($this->options, $options);

        $this->setCache($this->options['cache_dir']);
        $this->minifier = $minifier;
    }

    /**
     * Adds a stylesheets file.
     *
     * @param string $stylesheet A stylesheet file path
     * @param array  $attributes An array of attributes
     */
    public function add($stylesheet, $attributes = array())
    {
        $this->stylesheets[$stylesheet] = $attributes;
    }

    /**
     * Returns HTML representation of the links to stylesheets.
     *
     * @return string The HTML representation of the stylesheets
     */
    public function render()
    {
        $html = '';

        foreach ($this->stylesheets as $path => $attributes) {
            if (is_file($this->assetHelper->getWebPath().$path)) {
                $this->resources[] = new FileResource($this->assetHelper->getWebPath().$path);
            } else {
                $html .= $this->getHtml($this->assetHelper->getUrl($path), $attributes);

                $this->delete($path);
            }
        }

        if (!empty($this->stylesheets)) {
            $this->stylesheets = array_keys($this->stylesheets);
            $md5 = md5(implode($this->stylesheets));

            if ($this->needsReload($md5)) {
                $dumper = new $this->options['dumper_class']($this->stylesheets, array(
                    'web_dir'           => $this->assetHelper->getWebPath(),
                    'script_name_uri'   => $this->assetHelper->isScriptNameInUri()
                ));

                $dump = $dumper->dump();

                if ($this->options['minify']) {
                    $dump = $this->minifier->minify($dump);
                }

                $this->updateCache($md5, $dump);
            }

            $this->reset();
            $html .= $this->getHtml($this->assetHelper->generate('_pm_get', array(
                'file' => $md5,
                '_format' => 'css',
            )));
        }

        return $html;
    }

    public function setCache($cache)
    {
        if (!$cache) {
            throw new \InvalidArgumentException('The option \'cache_dir\' is required for PackingMinifyBundleBundle.');
        }

        if (!is_dir($cache)) {
            mkdir($cache);
        }

        if (!is_dir($cache.DIRECTORY_SEPARATOR.'css')) {
            mkdir($cache.DIRECTORY_SEPARATOR.'css');
        }

        if (!is_dir($cache.DIRECTORY_SEPARATOR.'js')) {
            mkdir($cache.DIRECTORY_SEPARATOR.'js');
        }
    }

    protected function getHtml($path, array $attributes = array())
    {
        $atts = '';
        foreach ($attributes as $key => $value) {
            $atts .= ' '.sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES, $this->charset));
        }

        return sprintf('<link href="%s" rel="stylesheet" type="text/css"%s />', $path, $atts)."\n";
    }

    protected function delete($stylesheet)
    {
        unset($this->stylesheets[$stylesheet]);
    }

    protected function reset()
    {
        $this->stylesheets = array();
    }

    protected function updateCache($file, $dump)
    {
        $this->writeCacheFile($this->getCacheFile($file), $dump);

        if ($this->options['debug']) {
            $this->writeCacheFile($this->getCacheFile($file, 'meta'), serialize($this->resources));
        }
    }

    protected function needsReload($file)
    {
        $cache = $this->getCacheFile($file);
        if (!file_exists($cache)) {
            return true;
        }

        if (!$this->options['debug']) {
            return false;
        }

        $metadata = $this->getCacheFile($file, 'meta');
        if (!file_exists($metadata)) {
            return true;
        }

        $time = filemtime($cache);
        $meta = unserialize(file_get_contents($metadata));
        foreach ($meta as $resource) {
            if (!$resource->isUptodate($time)) {
                return true;
            }
        }

        return false;
    }

    protected function getCacheFile($file, $extension = 'css')
    {
        return $this->options['cache_dir'].DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$file.'.'.$extension;
    }

    /**
     * @throws \RuntimeException When cache file can't be wrote
     */
    protected function writeCacheFile($file, $content)
    {
        $tmpFile = tempnam(dirname($file), basename($file));
        if (false !== @file_put_contents($tmpFile, $content) && @rename($tmpFile, $file)) {
            chmod($file, 0644);

            return;
        }

        throw new \RuntimeException(sprintf('Failed to write cache file "%s".', $file));
    }
}