<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Course;
use AppBundle\Form\Course\Main\CreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
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
        $em = $this->getDoctrine()->getManager();

        $courses = $em
            ->getRepository(Course::class)
            ->findBy([
                'author' => $this->getUser(),
            ])
        ;

        return $this->render(
            'AppBundle:Main/Course:list.html.twig',
            [
                'courses' => $courses,
            ]
        );
    }

    /**
     * Create a new Course entity.
     *
     * @Route("/create", name="app_main_courses_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $course = new Course();
        $form = $this->createForm(CreateType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.course.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_main_courses_list');
        }

        return $this->render(
            'AppBundle:Main/Course:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * List all Course entities.
     *
     * @Route("/{id}/show", name="app_main_courses_show")
     * @Method("GET")
     *
     * @param Course $course
     *
     * @return Response
     */
    public function showAction(Course $course)
    {
        return $this->render('AppBundle:Main/Course:list.html.twig');
    }
}
