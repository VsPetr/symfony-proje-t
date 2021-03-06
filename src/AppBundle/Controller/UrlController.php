<?php

// src/AppBundle/Controller/UrlController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Url;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UrlController extends Controller
{
    /**
     * @Route("/{shortUrl}")
     */
    public function showAction($shortUrl)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Url');
        $url = $repository->findOneByshortUrl($shortUrl)->getfullUrl();

        if (!$url) {
            throw $this->createNotFoundException(
                'No url found for url ' . $shortUrl
            );
        }

        return $this->redirect($url);
    }

}
