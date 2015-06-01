<?php

namespace App\RestclientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {

        return $this->render('AppRestclientBundle:Default:index.html.twig', array('name' => $name));
    }
}
