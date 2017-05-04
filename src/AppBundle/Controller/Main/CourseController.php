<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Course;
use AppBundle\Entity\User;
use AppBundle\Form\Course\Main\CreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * List user Course entities.
     *
     * @Route(options={"expose"=true}, name="app_main_courses_list")
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
     * TODO: change to show courses for explore page
     *
     * List all Course entities.
     *
     * @Route("/explore", name="app_main_courses_explore")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function exploreAction()
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
     * TODO: change to show courses for starred page
     *
     * List user starred Course entities
     *
     * @Route("/starred", name="app_main_courses_starred")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function starredAction()
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
     * @return Response|RedirectResponse
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
     * Edit a Course entity.
     *
     * @Route("/{id}/edit", name="app_main_courses_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Course  $course
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
                'app_main_courses_show',
                [
                    'id' => $course->getId(),
                ])
            ;
        }

        return $this->render(
            'AppBundle:Main/Course:edit.html.twig',
            [
                'course' => $course,
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
        return $this->render(
            'AppBundle:Main/Course:show.html.twig',
            [
                'course' => $course,
            ]
        );
    }

    /**
     * Deletes a Course entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_main_courses_delete")
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param Course     $course
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Course $course)
    {
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

        $em = $this->getDoctrine()->getManager();
        $em->remove($course);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        return $this->redirectToRoute('app_main_courses_list');
    }

    /**
     * Add one associate professor for a Course entity
     *
     * @Route("/{id}/add-associate-professor", options={"expose"=true}, name="app_main_courses_add_associate_professor")
     *
     * @param Request $request
     * @param Course  $course
     *
     * @return JsonResponse
     */
    public function addAssociateProfessor(Request $request, Course $course)
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $request->query->get('userId');

        $user = $em
            ->getRepository(User::class)
            ->find($userId)
        ;
        $assistants = $course->getAssistants();

        if (!$assistants->contains($user)) {
            $course->addAssistant($user);

            $em->persist($course);
            $em->flush();
        }

        $message = [
            'added' => 'success',
        ];

        return new JsonResponse($message);
    }

    /**
     * Get all associate professor for a Course entity
     *
     * @Route("/{id}/associate-professor/list", options={"expose"=true}, name="app_main_courses_associate_professors_list")
     *
     * @param Course $course
     *
     * @return Response
     */
    public function listAssociateProfessorsAction(Course $course)
    {
        return $this->render(
            'AppBundle:Main/Course:associates_component.html.twig',
            [
                'course' => $course,
            ]
        );
    }

    /**
     * Remove associate professor for a Course entity
     *
     * @Route("/{id}/associate-professor/remove", options={"expose"=true}, name="app_main_courses_associate_professors_remove")
     *
     * @param Request $request
     * @param Course  $course
     *
     * @return Response
     */
    public function removeAssociateProfessorAction(Request $request, Course $course)
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $request->query->get('userId');

        $user = $em
            ->getRepository(User::class)
            ->find($userId)
        ;

        $course->removeAssistant($user);
        $em->persist($course);
        $em->flush();

        return $this->render(
            'AppBundle:Main/Course:associates_component.html.twig',
            [
                'course' => $course,
            ]
        );
    }

    /**
     * Template for associate professors for a Course entity.
     *
     * @Route("/{id}/associate-professors", options={"expose"=true}, name="app_main_courses_associate_professors")
     * @Method({"GET"})
     *
     * @param Course $course
     *
     * @return Response
     */
    public function associateProfessors(Course $course)
    {
        $professors = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(User::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Main/Course:associate_professors.html.twig',
            [
                'course' => $course,
                'professors' => $professors,
            ]
        );
    }
}
