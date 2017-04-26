<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default controller.
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

    /**
     * @Route("/test", name="app_login")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        return $this->render('AppBundle:Main/Default:login.html.twig');
    }

    /**
     * @Route("/test/forgot-password", name="app_forgot_password")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function forgotPassword(Request $request)
    {
        return $this->render('AppBundle:Main/Default:forgot_password.html.twig');
    }
}
