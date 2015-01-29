<?php

namespace CampaignChain\Operation\GoToWebinarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function newAction($name)
    {
        return $this->render('CampaignChainOperationGoToWebinarBundle:Default:index.html.twig', array('name' => $name));
    }
}
