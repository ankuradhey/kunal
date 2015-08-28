<?php
namespace ScnSocialAuth\Controller;

use Hybrid_EndPoint;
use Zend\Mvc\Controller\AbstractActionController;

class HybridAuthController extends AbstractActionController
{
    public function indexAction()
    {
        \Hybrid_Endpoint::process();
    }
}
