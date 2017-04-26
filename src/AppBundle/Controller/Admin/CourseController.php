<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Course;
use AppBundle\Form\Course\CreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Course admin controller.
 *
 * @Route("/admin/course")
 */
class CourseController extends BaseController
{
    /**
     * Lists all Course entities.
     *
     * @Route("/list", name="app_admin_course_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $courses = $em
            ->getRepository(Course::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Course:list.html.twig',
            [
                'courses' => $courses,
            ]
        );
    }

    /**
     * Lists all Course entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_course_list_filtered")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(Course::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Course entity.
     *
     * @Route("/create", name="app_admin_course_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $course = new Course();
        $form = $this->createForm(CreateType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            return $this->redirectToRoute(
                'app_admin_course_show',
                [
                    'id' => $course->getId(),
                ])
            ;
        }

        return $this->render(
            'AppBundle:Admin/Course:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Course entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_course_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request    $request
     * @param Course     $course
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Course $course)
    {
        $form = $this->createForm(CreateType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course->setUpdatedAt(new \DateTime());

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
                        ->trans('success.course.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_admin_course_show',
                [
                    'id' => $course->getId(),
                ])
            ;
        }

        return $this->render(
            'AppBundle:Admin/Course:edit.html.twig',
            [
                'id' => $course->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays an Course entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_course_show")
     * @Method({"GET"})
     *
     * @param Course $course
     *
     * @return Response
     */
    public function showAction(Course $course)
    {
        return $this->render(
            'AppBundle:Admin/Course:show.html.twig',
            [
                'course' => $course,
            ]
        );
    }

    /**
     * Deletes a Course entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="app_admin_course_delete")
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param Course     $course
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Course $course)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($course);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.course.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_course_list');
    }
}
