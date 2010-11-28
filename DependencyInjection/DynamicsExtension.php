<?php

namespace Bundle\DynamicsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class DynamicsExtension extends Extension
{
    /**
     * Loads the I18nRouting configuration.
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
        $loader->load('dynamics.xml');

        $container->setParameter('templating.options.javascripts.minify', isset($config['js']['minify']) ? $config['js']['minify'] : true);
        $container->setParameter('templating.options.stylesheets.minify', isset($config['css']['minify']) ? $config['css']['minify'] : true);
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
        return 'http://www.apercite.fr/schema/dic/DynamicsBundle';
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
        return 'dynamics';
    }
}