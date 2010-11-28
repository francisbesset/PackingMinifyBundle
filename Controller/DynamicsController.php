<?php

namespace Bundle\DynamicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DynamicsController extends Controller
{
    public function getAction($file)
    {
        if (false !== $content = $this->get('dynamics')->get($file, $this->get('request')->getRequestFormat())) {
            return new Response($content);
        }

        return new Response(null, 404);
    }
}