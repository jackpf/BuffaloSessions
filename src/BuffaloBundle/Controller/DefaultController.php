<?php

namespace BuffaloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $videos = $this->getDoctrine()->getManager()
            ->getRepository('BuffaloBundle:MediaFile')
            ->findAll();

        return $this->render('BuffaloBundle:Buffalo:index.html.twig', ['videos' => $videos]);
    }
}
