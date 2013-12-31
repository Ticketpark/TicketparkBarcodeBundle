<?php

namespace Ticketpark\BarcodeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TicketparkBarcodeBundle:Default:index.html.twig', array('name' => $name));
    }
}
