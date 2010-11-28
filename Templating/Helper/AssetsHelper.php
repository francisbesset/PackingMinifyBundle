<?php

namespace Bundle\PackingMinifyBundle\Templating\Helper;

use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper as BaseAssetsHelper;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper;
use Symfony\Component\HttpFoundation\Request;

class AssetsHelper extends BaseAssetsHelper
{
    protected $webPath;
    protected $routerHelper;
    protected $script;

    /**
     * Constructor.
     *
     * @param Request      $request  A Request instance
     * @param string|array $baseURLs The domain URL or an array of domain URLs
     * @param string       $version  The version
     */
    public function __construct(Request $request, RouterHelper $routerHelper, $baseURLs = array(), $version = null)
    {
        parent::__construct($request, $baseURLs, $version);

        if (preg_match('#'.preg_quote($request->server->get('SCRIPT_NAME')).'#', $request->server->get('REQUEST_URI'))) {
            $this->script = true;
        }

        $this->setWebPath(preg_replace('#'.preg_quote(basename($request->server->get('SCRIPT_NAME')), '#').'$#', '', $request->server->get('SCRIPT_FILENAME')));
        $this->routerHelper = $routerHelper;
    }

    public function generate($name, array $parameters = array(), $absolute = false)
    {
        return $this->routerHelper->generate($name, $parameters, $absolute);
    }

    public function isScriptNameInUri()
    {
        return (boolean)$this->script;
    }

    public function getWebPath()
    {
        return $this->webPath;
    }

    public function setWebPath($webPath)
    {
        $this->webPath = $webPath;
    }
}