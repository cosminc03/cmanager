<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Course;
use AppBundle\Entity\Module;
use AppBundle\Form\Module\Main\CreateType;
use AppBundle\Form\Module\Main\EditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ModuleController.
 *
 * @Route("/modules")
 */
class ModuleController extends BaseController
{
    /**
     * Create a new Module entity.
     *
     * @Route("/create", name="app_main_modules_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $getParams = $request->query;

        if (!empty($getParams)) {
            $courseId = $getParams->get('courseId');
        }

        $module = new Module();
        $form = $this->createForm(CreateType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module->setAuthor($this->getUser());

            if ($courseId) {
                $course = $em
                    ->getRepository(Course::class)
                    ->find($courseId)
                ;

                if ($course) {
                    $module->setCourse($course);
                }
            }

            $em->persist($module);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.course_module.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_main_courses_course_modules',
               [
                   'id' => $courseId,
               ]
            );
        }

        return $this->render(
            'AppBundle:Main/Module:create.html.twig',
            [
                'form' => $form->createView(),
                'courseId' => $courseId,
            ]
        );
    }

    /**
     * Edit Module entity.
     *
     * @Route("/{id}/edit", name="app_main_modules_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Module  $module
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Module $module)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module->setUpdatedAt(new \DateTime());

            $em->persist($module);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.course_module.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_main_courses_course_modules',
                [
                    'id' => $module->getCourse()->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Main/Module:edit.html.twig',
            [
                'form' => $form->createView(),
                'module' => $module,
            ]
        );
    }

    /**
     * Display Module entities.
     *
     * @Route("/{id}/show", name="app_main_modules_show")
     * @Method("GET")
     *
     * @param Module $module
     *
     * @return Response
     */
    public function showAction(Module $module)
    {
        return $this->render(
            'AppBundle:Main/Module:show.html.twig',
            [
                'module' => $module,
            ]
        );
    }

    /**
     * Deletes a Module entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_main_modules_delete")
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param Module     $module
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Module $module)
    {
        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.course_module.delete.from_edit', [], 'flashes')
            )
        ;

        $course = $module->getCourse();

        $em = $this->getDoctrine()->getManager();
        $em->remove($module);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        return $this->redirectToRoute(
            'app_main_courses_course_modules',
            [
                'id' => $course->getId(),
            ]
        );
    }
}