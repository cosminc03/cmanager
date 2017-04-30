<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CourseController.
 *
 * @Route("/courses")
 */
class CourseController extends BaseController
{
    /**
     * List all Course entities.
     *
     * @Route(name="app_main_courses_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        return $this->render('AppBundle:Main/Course:list.html.twig');
    }

    /**
     * Create a new Course entity.
     *
     * @Route("/create", name="app_main_courses_create")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function createAction()
    {
        return $this->render('AppBundle:Main/Course:create.html.twig');
    }
}
