<?php

namespace BeSimple\PackingMinifyBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class BeSimplePackingMinifyExtension extends Extension
{
    /**
     * Loads the PackingMinify configuration.
     *
     * @param array            $config    An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        foreach ($configs as $config) {
            $this->registerMergeConfiguration($config, $container);
        }
    }

    protected function registerMergeConfiguration($config, $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (!$container->hasDefinition('packing_minify')) {
            $loader->load('packing_minify.xml');
        }

        if (!isset($config['js']['minify'])) {
            $config['js']['minify'] = true;
        }
        $container->setParameter('templating.options.javascripts.minify', $config['js']['minify']);

        if (!isset($config['css']['minify'])) {
            $config['css']['minify'] = true;
        }
        $container->setParameter('templating.options.stylesheets.minify', $config['css']['minify']);

        if (isset($config['js']['minifier'])) {
            $config['js']['minifier'] = strtolower($config['js']['minifier']);
        } else {
            $config['js']['minifier'] = 'jsmin';
        }
        $container->setAlias('templating.minifier.javascripts', 'templating.minifier.javascripts.'.$config['js']['minifier']);

        if (isset($config['css']['minifier'])) {
            $config['css']['minifier'] = strtolower($config['css']['minifier']);
        } else {
            $config['css']['minifier'] = 'basic';
        }
        $container->setAlias('templating.minifier.stylesheets', 'templating.minifier.stylesheets.'.$config['css']['minifier']);

        if (isset($config['js']['options'])) {
            $container->setParameter('templating.minifier.javascripts.'.$config['js']['minifier'].'.options', $config['js']['options']);
        } else {
            $container->setParameter('templating.minifier.javascripts.'.$config['js']['minifier'].'.options', array());
        }

        if (isset($config['css']['options'])) {
            $container->setParameter('templating.minifier.stylesheets.'.$config['css']['minifier'].'.options', $config['css']['options']);
        } else {
            $container->setParameter('templating.minifier.stylesheets.'.$config['css']['minifier'].'.options', array());
        }
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'be_simple_packing_minify';
    }
}
