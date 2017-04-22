<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default admin controller.
 *
 * @Route("/admin")
 */
class DefaultController extends BaseController
{
    /**
     * @Route("/home", name="app_admin_default_homepage")
     */
    public function adminAction(Request $request)
    {
        return $this->render('AppBundle:Admin:home.html.twig');
    }
}
