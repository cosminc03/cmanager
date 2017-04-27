<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller.
 */
class DefaultController extends BaseController
{
    /**
     * @Route("/", name="app_main_default_homepage")
     */
    public function adminAction(Request $request)
    {
        return $this->render('AppBundle:Main:homepage.html.twig');
    }

    /**
     * @Route("/test", name="app_main_defaut_test")
     */
    public function testAction()
    {
        return $this->render('AppBundle:Main:test.html.twig');
    }
}
