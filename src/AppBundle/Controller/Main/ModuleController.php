<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Course;
use AppBundle\Entity\File;
use AppBundle\Entity\Module;
use AppBundle\Form\Module\Main\CreateType;
use AppBundle\Form\Module\Main\EditType;
use AppBundle\Form\File\Main\CreateType as FileCreateType;
use AppBundle\Security\CourseVoter;
use AppBundle\Security\ModuleVoter;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
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
     * @Route("/course/{id}/show", options={"expose"=true}, name="app_main_modules_course_show")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Module  $module
     *
     * @return Response
     */
    public function showAction(Request $request, Module $module)
    {
        $file = new File();
        $fileUploadForm = $this->createForm(FileCreateType::class, $file);
        $fileUploadForm->handleRequest($request);

        if ($fileUploadForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // $fileUploaded stores the uploaded file
            /** @var UploadedFile $file */
            $uploadedFile = $fileUploadForm->get('uploadedFile')->getData();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$uploadedFile->guessExtension();

            $savePath = $this->getParameter('app.course.attachments_path');
            $savePath = $savePath.'/'.$module->getCourse()->getAbbreviation();
            // Move the file to the directory where brochures are stored
            $uploadedFile->move(
                $savePath,
                $fileName
            );

            if ($file->getOriginalName()) {
                $originalName = $file->getOriginalName().'.'.$uploadedFile->guessExtension();
            } else {
                $originalName = $uploadedFile->getClientOriginalName();
            }

            $file->setName($fileName);
            $file->setOriginalName($originalName);
            $file->setModule($module);

            $em->persist($file);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.uploaded_file.added', [], 'flashes')
                )
            ;

            $this->redirectToRoute(
                'app_main_modules_course_show',
                [
                    'id' => $module->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Main/Module:show_course.html.twig',
            [
                'module' => $module,
                'userId' => $this->getUser()->getId(),
                'userFullName' => $this->getUser()->getFullName(),
                'fileUploadForm' => $fileUploadForm->createView(),
            ]
        );
    }

    /**
     * Display Seminar Module entities.
     *
     * @Route("/seminar/{id}/show", options={"expose"=true}, name="app_main_modules_seminar_show")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Module  $module
     *
     * @return Response
     */
    public function showSeminarAction(Request $request, Module $module)
    {
        $file = new File();
        $fileUploadForm = $this->createForm(FileCreateType::class, $file);
        $fileUploadForm->handleRequest($request);

        if ($fileUploadForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // $fileUploaded stores the uploaded file
            /** @var UploadedFile $file */
            $uploadedFile = $fileUploadForm->get('uploadedFile')->getData();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$uploadedFile->guessExtension();

            $savePath = $this->getParameter('app.course.attachments_path');
            $savePath = $savePath.'/'.$module->getCourse()->getAbbreviation();
            // Move the file to the directory where brochures are stored
            $uploadedFile->move(
                $savePath,
                $fileName
            );

            if ($file->getOriginalName()) {
                $originalName = $file->getOriginalName().'.'.$uploadedFile->guessExtension();
            } else {
                $originalName = $uploadedFile->getClientOriginalName();
            }

            $file->setName($fileName);
            $file->setOriginalName($originalName);
            $file->setModule($module);

            $em->persist($file);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.uploaded_file.added', [], 'flashes')
                )
            ;

            $this->redirectToRoute(
                'app_main_modules_seminar_show',
                [
                    'id' => $module->getId(),
                ]
            );
        }

        $hideAuxNav = false;
        if ($module->getCourse()->getAuthor() == $this->getUser()) {
            $hideAuxNav = true;
        }

        return $this->render(
            'AppBundle:Main/Module:show_seminar.html.twig',
            [
                'module' => $module,
                'userId' => $this->getUser()->getId(),
                'userFullName' => $this->getUser()->getFullName(),
                'fileUploadForm' => $fileUploadForm->createView(),
                'hideAuxNav' => $hideAuxNav,
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

    /**
     * Upload new file for a Module entity.
     *
     * @Route("/uploaded-file/{id}/delete", options={"expose"=true}, name="app_main_modules_delete_uploaded_file")
     * @Method({"GET"})
     *
     * @param File $file
     *
     * @return JsonResponse
     */
    public function deleteUploadedFileAction(File $file)
    {
        $path = $this->getParameter('app.course.attachments_path');
        $pathToFile = $path.'/'.$file->getModule()->getCourse()->getAbbreviation().'/'.$file->getName();

        $fs = new Filesystem();
        $fs->remove($pathToFile);

        $em = $this->getDoctrine()->getManager();
        $em->remove($file);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.uploaded_file.delete', [], 'flashes')
            )
        ;

        $message = [
            'delete' => 'success',
        ];

        return new JsonResponse($message);
    }

    /**
     * Download file associated with a Module entity.
     *
     * @Route("/download-file/{id}", options={"expose"=true}, name="app_main_modules_download_file")
     * @Method({"GET"})
     *
     * @param File $file
     *
     * @return Response|JsonResponse
     */
    public function downloadFileAction(File $file)
    {
        try {
            $displayName = $file->getOriginalName();
            $fileName = $file->getName();
            $path = $this->getParameter('app.course.attachments_path');
            $filePath = $path."/".$file->getModule()->getCourse()->getAbbreviation()."/".$fileName;

            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_INLINE,
                $displayName
            );

            return $response;
        } catch (Exception $e) {
            $array = [
                'status' => 0,
                'message' => 'Download error',
            ];
            $response = new JsonResponse($array, 400);

            return $response;
        }
    }
}