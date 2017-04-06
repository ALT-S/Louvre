<?php

namespace ALT\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ALTAppBundle:Default:index.html.twig');
    }
}
