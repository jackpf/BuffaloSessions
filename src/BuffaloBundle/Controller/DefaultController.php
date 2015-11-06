<?php

namespace BuffaloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BuffaloBundle:Default:index.html.twig');
    }
}
