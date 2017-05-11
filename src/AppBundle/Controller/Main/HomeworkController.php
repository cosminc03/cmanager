<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Course;
use AppBundle\Entity\Homework;
use AppBundle\Form\Homework\Main\CreateType;
use AppBundle\Security\CourseVoter;
use AppBundle\Security\HomeworkVoter;
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

            if ($form->get('attachmentFile')->getData()) {
                /** @var UploadedFile $attachment */
                $attachment = $form->get('attachmentFile')->getData();
                $homework->setAttachmentOriginalName($attachment->getClientOriginalName());
            }

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

            if ($form->get('attachmentFile')->getData()) {
                /** @var UploadedFile $attachment */
                $attachment = $form->get('attachmentFile')->getData();
                $homework->setAttachmentOriginalName($attachment->getClientOriginalName());
            }

            $em->persist($homework);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.edit.create', [], 'flashes')
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
     * @Route("/{id}/show", name="app_main_homework_show")
     * @Method("GET")
     *
     * @param Homework $homework
     *
     * @return Response
     */
    public function showSeminarAction(Homework $homework)
    {
        return $this->render(
            'AppBundle:Main/Homework:show.html.twig',
            [
                'homework' => $homework,
                'course' => $homework->getCourse(),
                'userId' => $this->getUser()->getId(),
                'userFullName' => $this->getUser()->getFullName(),
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
     * Get attachment for a Homework entity.
     *
     * @Route("/{id}/download-attachment", name="app_main_homework_download_attachment")
     *
     * @param Homework $homework
     *
     * @return BinaryFileResponse
     */
    public function downloadAttachmentAction(Homework $homework)
    {
        $path = $this->getParameter('app.course.attachments_path');
        $filePath = $path.'/'.$homework->getCourse()->getAbbreviation().'/'.$homework->getAttachmentName();
        $response = new BinaryFileResponse($filePath);

        $nameComponents = explode('.', $homework->getAttachmentOriginalName());

        if ($nameComponents[1] === 'pdf') {
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_INLINE,
                $homework->getAttachmentOriginalName()
            );
        } else {
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $homework->getAttachmentOriginalName()
            );
        }

        return $response;
    }
}
