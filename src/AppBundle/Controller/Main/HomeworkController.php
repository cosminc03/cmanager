<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Course;
use AppBundle\Entity\File;
use AppBundle\Entity\Homework;
use AppBundle\Form\Homework\Main\CreateType;
use AppBundle\Security\CourseVoter;
use AppBundle\Security\HomeworkVoter;
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
use AppBundle\Form\File\Main\CreateType as FileCreateType;

/**
 * Class HomeworkController.
 *
 * @Route("/homework")
 */
class HomeworkController extends BaseController
{
    /**
     * Create a new Homework entity.
     *
     * @Route("/create", name="app_main_homework_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $homework = new Homework();
        $isCourseHomework = false;
        $em = $this->getDoctrine()->getManager();
        $getParams = $request->query;

        if (!empty($getParams)) {
            $courseId = $getParams->get('courseId');

            if ($getParams->get('isCourseHomework') && $getParams->get('isCourseHomework') == '1') {
                $isCourseHomework = true;
            }

            $course = $em
                ->getRepository(Course::class)
                ->find($courseId)
            ;
        } else {
            $course = null;
        }

        $this->denyAccessUnlessGranted(CourseVoter::CREATE_HOMEWORK, $course);

        $form = $this->createForm(CreateType::class, $homework);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $homework->setAuthor($this->getUser());
            $homework->setIsCourseHomework($isCourseHomework);
            $homework->setCourse($course);

            $em->persist($homework);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.homework.create', [], 'flashes')
            );

            if ($isCourseHomework) {
                return $this->redirectToRoute(
                    'app_main_courses_list_homework',
                    [
                        'id' => $courseId,
                    ]
                );
            } else {
                return $this->redirectToRoute(
                    'app_main_courses_users_homework',
                    [
                        'id' => $courseId,
                        'userId' => $this->getUser()->getId(),
                    ]
                );
            }

        }

        return $this->render(
            'AppBundle:Main/Homework:create.html.twig',
            [
                'form' => $form->createView(),
                'courseId' => $courseId,
                'isCourseHomework' => $isCourseHomework,
            ]
        );
    }

    /**
     * Edit a Homework entity.
     *
     * @Route("/{id}/edit", name="app_main_homework_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request  $request
     * @param Homework $homework
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Homework $homework)
    {
        $isCourseHomework = false;
        $em = $this->getDoctrine()->getManager();
        $getParams = $request->query;

        $this->denyAccessUnlessGranted(HomeworkVoter::EDIT, $homework);

        if (!empty($getParams)) {
            if ($getParams->get('isCourseHomework') && $getParams->get('isCourseHomework') == '1') {
                $isCourseHomework = true;
            }
        }

        $form = $this->createForm(CreateType::class, $homework);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $homework->setAuthor($this->getUser());
            $homework->setIsCourseHomework($isCourseHomework);

            $em->persist($homework);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.homework.edit', [], 'flashes')
            );

            if ($isCourseHomework) {

                return $this->redirectToRoute(
                    'app_main_courses_list_homework',
                    [
                        'id' => $homework->getCourse()->getId(),
                    ]
                );
            } else {
                return $this->redirectToRoute(
                    'app_main_courses_users_homework',
                    [
                        'id' => $homework->getCourse()->getId(),
                        'userId' => $this->getUser()->getId(),
                    ]
                );
            }

        }

        return $this->render(
            'AppBundle:Main/Homework:edit.html.twig',
            [
                'form' => $form->createView(),
                'isCourseHomework' => $isCourseHomework,
                'homework' => $homework,
            ]
        );
    }

    /**
     * Display Homework entities.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_main_homework_show")
     * @Method({"GET", "POST"})
     *
     * @param Request  $request
     * @param Homework $homework
     *
     * @return Response
     */
    public function showSeminarAction(Request $request, Homework $homework)
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
            $savePath = $savePath.'/'.$homework->getCourse()->getAbbreviation();
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
            $file->setHomework($homework);

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
                'app_main_homework_show',
                [
                    'id' => $homework->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Main/Homework:show.html.twig',
            [
                'homework' => $homework,
                'course' => $homework->getCourse(),
                'userId' => $this->getUser()->getId(),
                'userFullName' => $this->getUser()->getFullName(),
                'fileUploadForm' => $fileUploadForm->createView(),
            ]
        );
    }

    /**
     * Deletes a Course Module entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_main_homework_delete")
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param Homework   $homework
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Homework $homework)
    {
        $this->denyAccessUnlessGranted(HomeworkVoter::DELETE, $homework);

        $flashBag =  $this->get('session')->getFlashBag();
        $flashBag->set(
            'success',
            $this
                ->get('translator')
                ->trans('success.homework.delete.from_edit', [], 'flashes')
        );


        $course = $homework->getCourse();

        $em = $this->getDoctrine()->getManager();
        $em->remove($homework);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        return $this->redirectToRoute(
            'app_main_courses_users_homework',
            [
                'id' => $course->getId(),
                'userId' => $this->getUser()->getId(),
            ]
        );
    }

    /**
     * Upload new file for a Module entity.
     *
     * @Route("/uploaded-file/{id}/delete", options={"expose"=true}, name="app_main_homework_delete_uploaded_file")
     * @Method({"GET"})
     *
     * @param File $file
     *
     * @return JsonResponse
     */
    public function deleteUploadedFileAction(File $file)
    {
        $path = $this->getParameter('app.course.attachments_path');
        $pathToFile = $path.'/'.$file->getHomework()->getCourse()->getAbbreviation().'/'.$file->getName();

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
     * @Route("/download-file/{id}", options={"expose"=true}, name="app_main_homework_download_file")
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
            $filePath = $path."/".$file->getHomework()->getCourse()->getAbbreviation()."/".$fileName;

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
