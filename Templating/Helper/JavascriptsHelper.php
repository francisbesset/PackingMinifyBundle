<?php

namespace Bundle\PackingMinifyBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Bundle\PackingMinifyBundle\Templating\Minifier\Minifier;
use Bundle\PackingMinifyBundle\Templating\Resource\FileResource;

/**
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author Francis Besset   <http://www.github.com/francisbesset/>
 */
class JavascriptsHelper extends Helper
{
    protected $javascripts = array();
    protected $assetHelper;
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
    public function __construct(AssetsHelper $assetHelper, Minifier $minifier, array $options = array())
    {
        $this->assetHelper = $assetHelper;

        $this->options = array(
            'cache_dir'    => null,
            'debug'        => false,
            'minify'       => false,
            'dumper_class' => 'Bundle\\PackingMinifyBundle\\Templating\\Dumper\\JavascriptsDumper',
        );

        // check option names
        if ($diff = array_diff(array_keys($options), array_keys($this->options))) {
            throw new \InvalidArgumentException(sprintf('The JavascriptsHelper does not support the following options: \'%s\'.', implode('\', \'', $diff)));
        }

        $this->options = array_merge($this->options, $options);

        $this->setCache($this->options['cache_dir']);
        $this->minifier = $minifier;
    }

    /**
     * Adds a stylesheets file.
     *
     * @param string $javascript A javascript file path
     * @param array  $attributes An array of attributes
     */
    public function add($javascript, $attributes = array())
    {
        $this->javascripts[$javascript] = $attributes;
    }

    /**
     * Returns all JavaScript files.
     *
     * @return array An array of JavaScript files to include
     */
    public function get()
    {
        return $this->javascripts;
    }

    /**
     * Returns HTML representation of the links to stylesheets.
     *
     * @return string The HTML representation of the stylesheets
     */
    public function render()
    {
        $html = '';

        foreach ($this->javascripts as $path => $attributes) {
            if (is_file($this->assetHelper->getWebPath().$path)) {
                $this->resources[] = new FileResource($this->assetHelper->getWebPath().$path);
            } else {
                $html .= $this->getHtml($this->assetHelper->getUrl($path), $attributes);

                $this->delete($path);
            }
        }

        if (!empty($this->javascripts)) {
            $this->javascripts = array_keys($this->javascripts);
            $md5 = md5(implode($this->javascripts));

            if ($this->needsReload($md5)) {
                $dumper = new $this->options['dumper_class']($this->javascripts, array(
                    'web_dir'         => $this->assetHelper->getWebPath(),
                    'script_name_uri' => $this->assetHelper->isScriptNameInUri()
                ));

                $dump = $dumper->dump();

                if ($this->options['minify']) {
                    $dump = $this->minifier->minify($dump);
                }

                $this->updateCache($md5, $dump);
            }

            $this->reset();
            $html .= $this->getHtml($this->assetHelper->generate('_pm_get', array(
                'file'    => $md5,
                '_format' => 'js',
            )));
        }

        return $html;
    }

    public function setCache($cache)
    {
        if (!$cache) {
            throw new \InvalidArgumentException('The option \'cache_dir\' is required for PackingMinifyBundle.');
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

    /**
     * Returns a string representation of this helper as HTML.
     *
     * @return string The HTML representation of the JavaScripts
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'javascripts';
    }

    protected function getHtml($path, array $attributes = array())
    {
        $atts = '';
        foreach ($attributes as $key => $value) {
            $atts .= ' '.sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES, $this->charset));
        }

        return sprintf('<script type="text/javascript" src="%s"%s></script>', $path, $atts)."\n";
    }

    protected function delete($javascript)
    {
        unset($this->javascripts[$javascript]);
    }

    protected function reset()
    {
        $this->javascripts = array();
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

    protected function getCacheFile($file, $extension = 'js')
    {
        return $this->options['cache_dir'].DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.$file.'.'.$extension;
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
