<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Course;
use AppBundle\Entity\Module;
use AppBundle\Form\Module\Main\CreateType;
use AppBundle\Form\Module\Main\EditType;
use AppBundle\Security\CourseVoter;
use AppBundle\Security\ModuleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class ModuleController.
 *
 * @Route("/modules")
 */
class ModuleController extends BaseController
{
    /**
     * Create a new Course Module entity.
     *
     * @Route("/course/create", name="app_main_modules_course_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createCourseAction(Request $request)
    {
        $module = new Module();
        $this->denyAccessUnlessGranted(ModuleVoter::CREATE_COURSE, $module);

        $em = $this->getDoctrine()->getManager();
        $getParams = $request->query;

        if (!empty($getParams)) {
            $courseId = $getParams->get('courseId');
        }

        $form = $this->createForm(CreateType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module->setAuthor($this->getUser());
            $module->setIsCourse(true);

            if ($form->get('attachmentFile')->getData()) {
                /** @var UploadedFile $attachment */
                $attachment = $form->get('attachmentFile')->getData();
                $module->setAttachmentOriginalName($attachment->getClientOriginalName());
            }

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

            $flashBag =$this->get('session')->getFlashBag();
            $flashBag->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.course_module.create', [], 'flashes')
            );

            return $this->redirectToRoute(
                'app_main_courses_course_modules',
                [
                    'id' => $courseId,
                ]
            );
        }

        return $this->render(
            'AppBundle:Main/Module:create_course.html.twig',
            [
                'form' => $form->createView(),
                'courseId' => $courseId,
            ]
        );
    }

    /**
     * Create a new Seminar Module entity.
     *
     * @Route("/seminar/create", name="app_main_modules_seminar_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createSeminarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $getParams = $request->query;

        if (!empty($getParams)) {
            $courseId = $getParams->get('courseId');
            $course = $em
                ->getRepository(Course::class)
                ->find($courseId)
            ;
        } else {
            $course = null;
        }

        $this->denyAccessUnlessGranted(CourseVoter::CREATE_SEMINAR, $course);

        $module = new Module();
        $form = $this->createForm(CreateType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module->setAuthor($this->getUser());
            $module->setIsSeminar(true);

            if ($form->get('attachmentFile')->getData()) {
                /** @var UploadedFile $attachment */
                $attachment = $form->get('attachmentFile')->getData();
                $module->setAttachmentOriginalName($attachment->getClientOriginalName());
            }

            if ($course) {
                $module->setCourse($course);
            }

            $em->persist($module);
            $em->flush();

            $flashBag =$this->get('session')->getFlashBag();
            $flashBag->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.seminar_module.create', [], 'flashes')
            );

            if ($module->getAuthor() == $this->getUser()) {
                return $this->redirectToRoute(
                    'app_main_courses_seminar_modules',
                    [
                        'id' => $courseId
                    ]
                );
            }

            return $this->redirectToRoute(
                'app_main_courses_users_seminars',
                [
                    'id' => $courseId,
                    'userId' => $this->getUser()->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Main/Module:create_seminar.html.twig',
            [
                'form' => $form->createView(),
                'courseId' => $courseId,
            ]
        );
    }

    /**
     * Edit Course Module entity.
     *
     * @Route("/course/{id}/edit", name="app_main_modules_course_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Module  $module
     *
     * @return Response|RedirectResponse
     */
    public function editCourseAction(Request $request, Module $module)
    {
        $this->denyAccessUnlessGranted(ModuleVoter::EDIT_COURSE, $module);

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module->setUpdatedAt(new \DateTime());

            if ($form->get('attachmentFile')->getData()) {
                /** @var UploadedFile $attachment */
                $attachment = $form->get('attachmentFile')->getData();
                $module->setAttachmentOriginalName($attachment->getClientOriginalName());
            }

            $em->persist($module);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->set(
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
            'AppBundle:Main/Module:edit_course.html.twig',
            [
                'form' => $form->createView(),
                'module' => $module,
            ]
        );
    }

    /**
     * Edit Seminar Module entity.
     *
     * @Route("/seminar/{id}/edit", name="app_main_modules_seminar_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Module  $module
     *
     * @return Response|RedirectResponse
     */
    public function editSeminarAction(Request $request, Module $module)
    {
        $this->denyAccessUnlessGranted(ModuleVoter::EDIT_SEMINAR, $module);

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module->setUpdatedAt(new \DateTime());

            if ($form->get('attachmentFile')->getData()) {
                /** @var UploadedFile $attachment */
                $attachment = $form->get('attachmentFile')->getData();
                $module->setAttachmentOriginalName($attachment->getClientOriginalName());
            }

            $em->persist($module);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.seminar_module.edit', [], 'flashes')
            );

            return $this->redirectToRoute(
                'app_main_courses_seminar_modules',
                [
                    'id' => $module->getCourse()->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Main/Module:edit_seminar.html.twig',
            [
                'form' => $form->createView(),
                'module' => $module,
            ]
        );
    }

    /**
     * Display Course Module entities.
     *
     * @Route("/course/{id}/show", name="app_main_modules_course_show")
     * @Method("GET")
     *
     * @param Module  $module
     *
     * @return Response
     */
    public function showAction(Module $module)
    {
        return $this->render(
            'AppBundle:Main/Module:show_course.html.twig',
            [
                'module' => $module,
                'userId' => $this->getUser()->getId(),
                'userFullName' => $this->getUser()->getFullName(),
            ]
        );
    }

    /**
     * Display Seminar Module entities.
     *
     * @Route("/seminar/{id}/show", name="app_main_modules_seminar_show")
     * @Method("GET")
     *
     * @param Module $module
     *
     * @return Response
     */
    public function showSeminarAction(Module $module)
    {
        return $this->render(
            'AppBundle:Main/Module:show_seminar.html.twig',
            [
                'module' => $module,
                'userId' => $this->getUser()->getId(),
                'userFullName' => $this->getUser()->getFullName(),
            ]
        );
    }

    /**
     * Deletes a Course Module entity.
     *
     * @Route("/course/{id}/delete", options={"expose"=true}, name="app_main_modules_course_delete")
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param Module     $module
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteCourseAction(Request $request, Module $module)
    {
        $this->denyAccessUnlessGranted(ModuleVoter::DELETE_COURSE, $module);

        $flashBag =  $this->get('session')->getFlashBag();
        $flashBag->set(
            'success',
            $this
                ->get('translator')
                ->trans('success.course_module.delete.from_edit', [], 'flashes')
        );


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

    /**
     * Deletes a Seminar Module entity.
     *
     * @Route("/seminar/{id}/delete", options={"expose"=true}, name="app_main_modules_seminar_delete")
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param Module     $module
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteSeminarAction(Request $request, Module $module)
    {
        $this->denyAccessUnlessGranted(ModuleVoter::DELETE_SEMINAR, $module);

        $flashBag =  $this->get('session')->getFlashBag();
        $flashBag->set(
            'success',
            $this
                ->get('translator')
                ->trans('success.seminar_module.delete.from_edit', [], 'flashes')
        );

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
            'app_main_courses_seminar_modules',
            [
                'id' => $course->getId(),
            ]
        );
    }

    /**
     * Get attachment for a Module entity.
     *
     * @Route("/{id}/download-attachment", name="app_main_modules_download_attachment")
     *
     * @param Module $module
     *
     * @return BinaryFileResponse
     */
    public function downloadAttachmentAction(Module $module)
    {
        $path = $this->getParameter('app.course.attachments_path');
        $filePath = $path.'/'.$module->getCourse()->getAbbreviation().'/'.$module->getAttachmentName();
        $response = new BinaryFileResponse($filePath);

        $nameComponents = explode('.', $module->getAttachmentOriginalName());

        if ($nameComponents[1] === 'pdf') {
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_INLINE,
                $module->getAttachmentOriginalName()
            );
        } else {
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $module->getAttachmentOriginalName()
            );
        }

        return $response;
    }
}
