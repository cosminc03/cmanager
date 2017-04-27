<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
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
     * @Route("/homepage", name="app_admin_default_homepage")
     */
    public function adminAction(Request $request)
    {
        dump('TEST');die;
        return $this->render('AppBundle:Admin:home.html.twig');
    }
}
