<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BeSimple\PackingMinifyBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use BeSimple\PackingMinifyBundle\Twig\TokenParser\StylesheetTokenParser;
use BeSimple\PackingMinifyBundle\Twig\TokenParser\StylesheetsTokenParser;
use BeSimple\PackingMinifyBundle\Twig\TokenParser\JavascriptTokenParser;
use BeSimple\PackingMinifyBundle\Twig\TokenParser\JavascriptsTokenParser;

/**
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class AssetsExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'stylesheet'  => new \Twig_Function_Method($this, 'addStylesheet'),
            'stylesheets' => new \Twig_Function_Method($this, 'renderStylesheet', array('is_safe' => array('html'))),
            'javascript'  => new \Twig_Function_Method($this, 'addJavascript'),
            'javascripts' => new \Twig_Function_Method($this, 'renderJavascript', array('is_safe' => array('html'))),
        );
    }

    public function addStylesheet($stylesheets)
    {
        if (!is_array($stylesheets)) {
            $stylesheets = array($stylesheets);
        }

        $this->container->get('templating.helper.stylesheets')->add($stylesheets);
    }

    public function renderStylesheet()
    {
        return $this->container->get('templating.helper.stylesheets')->render();
    }

    public function addJavascript($javascripts)
    {
        if (!is_array($javascripts)) {
            $javascripts = array($javascripts);
        }

        $this->container->get('templating.helper.javascripts')->add($javascripts);
    }

    public function renderJavascript()
    {
        return $this->container->get('templating.helper.javascripts')->render();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'assets';
    }
}