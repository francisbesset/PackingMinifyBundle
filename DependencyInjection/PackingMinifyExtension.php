<?php

namespace Bundle\PackingMinifyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class PackingMinifyExtension extends Extension
{
    /**
     * Loads the PackingMinify configuration.
     *
     * @param array            $config    An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function configLoad($config, ContainerBuilder $container)
    {
        $this->registerMergeConfiguration($config, $container);
    }

    protected function registerMergeConfiguration($config, $container)
    {
        $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config/schema');
        $loader->load('packing_minify.xml');

        if (isset($config['js']['minify'])) {
            $container->setParameter('templating.options.javascripts.minify', $config['js']['minify']);
        }

        if (isset($config['css']['minify'])) {
            $container->setParameter('templating.options.stylesheets.minify', $config['css']['minify']);
        }

        if (isset($config['js']['minifier'])) {
            $config['js']['minifier'] = strtolower($config['js']['minifier']);

            $container->setAlias('templating.minifier.javascripts', 'templating.minifier.javascripts.'.$config['js']['minifier']);
        }

        if (isset($config['css']['minifier'])) {
            $container->setAlias('templating.minifier.stylesheets', 'templating.minifier.stylesheets.'.$config['css']['minifier']);
        }

        if (isset($config['js']['options'])) {
            $container->setParameter('templating.minifier.javascripts.'.$config['js']['minifier'].'.options', $config['js']['options']);
        }
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     */
    public function getNamespace()
    {
        return 'http://www.apercite.fr/schema/dic/PackingMinifyBundle';
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
        return 'packingMinify';
    }
}