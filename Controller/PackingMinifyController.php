<?php

namespace BeSimple\PackingMinifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PackingMinifyController extends Controller
{
    public function getAction($file)
    {
        $format = $this->get('request')->getRequestFormat();

        if (false !== $content = $this->get('packing_minify')->get($file, $format)) {
            if ($format == 'css') {
                return new Response($content, 200, array('Content-Type' => 'text/css'));
            } else {
                return new Response($content, 200, array('Content-Type' => 'application/x-javascript'));
            }
        }

        throw new NotFoundHttpException('Is not a XML Http Request');
    }
}