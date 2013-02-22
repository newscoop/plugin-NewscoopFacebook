<?php

namespace AHS\FacebookNewscoopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AHSFacebookNewscoopBundle:Default:index.html.twig');
    }
}
