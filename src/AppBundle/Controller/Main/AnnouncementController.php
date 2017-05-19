<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Announcement;
use AppBundle\Entity\Course;
use AppBundle\Form\Announcement\Main\CreateType;
use AppBundle\Security\CourseVoter;
use AppBundle\Security\HomeworkVoter;
use AppBundle\Topic\CourseNotificationTopic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class AnnouncementController.
 *
 * @Route("/announcements")
 */
class AnnouncementController extends BaseController
{
    /**
     * Create a new Announcement entity.
     *
     * @Route("/create", name="app_main_announcements_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $announcement = new Announcement();
        $isCourseAnnouncement = false;
        $em = $this->getDoctrine()->getManager();
        $getParams = $request->query;

        if (!empty($getParams)) {
            $courseId = $getParams->get('courseId');

            if ($getParams->get('isCourseAnnouncement') && $getParams->get('isCourseAnnouncement') == '1') {
                $isCourseAnnouncement = true;
            }

            $course = $em
                ->getRepository(Course::class)
                ->find($courseId)
            ;
        } else {
            $course = null;
        }

        //$this->denyAccessUnlessGranted(CourseVoter::CREATE_HOMEWORK, $course);

        $form = $this->createForm(CreateType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $announcement->setCreatedBy($this->getUser());
            $announcement->setIsCourseAnnouncement($isCourseAnnouncement);
            $announcement->setCourse($course);

            $em->persist($announcement);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.announcement.create', [], 'flashes')
            );

            if ($isCourseAnnouncement) {
                // Push notification for new course creation
                if ($course->getSubscribers()->count() or $course->getAssistants()->count()) {
                    $pusher = $this->container->get('gos_web_socket.zmq.pusher');

                    $pusher->push(
                        ['type' => CourseNotificationTopic::NEW_COURSE_ANNOUNCEMENT],
                        'course_notification_topic',
                        ['id' => $course->getId()]
                    );

                    $message = 'notification.new_announcement';
                    $this
                        ->get('app.service.notification')
                        ->addNotificationByType(CourseNotificationTopic::NEW_COURSE_ANNOUNCEMENT, $this->getUser(), $course, $announcement, $message)
                    ;
                }

                return $this->redirectToRoute(
                    'app_main_courses_list_announcements',
                    [
                        'id' => $courseId,
                    ]
                );
            } else {
                return $this->redirectToRoute(
                    'app_main_courses_users_announcements',
                    [
                        'id' => $courseId,
                        'userId' => $this->getUser()->getId(),
                    ]
                );
            }

        }

        return $this->render(
            'AppBundle:Main/Announcement:create.html.twig',
            [
                'form' => $form->createView(),
                'courseId' => $courseId,
                'isCourseAnnouncement' => $isCourseAnnouncement,
            ]
        );
    }

    /**
     * Edit an Announcement entity.
     *
     * @Route("/{id}/edit", name="app_main_announcements_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param Announcement $announcement
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Announcement $announcement)
    {
        //$isCourseAnnouncement = false;
        $em = $this->getDoctrine()->getManager();
        $getParams = $request->query;

        //$this->denyAccessUnlessGranted(HomeworkVoter::EDIT, $homework);

        /*if (!empty($getParams)) {
            if ($getParams->get('isCourseAnnouncement') && $getParams->get('isCourseAnnouncement') == '1') {
                $isCourseAnnouncement = true;
            }
        }*/

        $isCourseAnnouncement = $announcement->getIsCourseAnnouncement();
        $form = $this->createForm(CreateType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $announcement->setUpdatedAt(new \DateTime());

            $em->persist($announcement);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.announcement.edit', [], 'flashes')
            );

            if ($isCourseAnnouncement) {

                return $this->redirectToRoute(
                    'app_main_courses_list_announcements',
                    [
                        'id' => $announcement->getCourse()->getId(),
                    ]
                );
            } else {
                return $this->redirectToRoute(
                    'app_main_courses_users_announcements',
                    [
                        'id' => $announcement->getCourse()->getId(),
                        'userId' => $this->getUser()->getId(),
                    ]
                );
            }
        }

        return $this->render(
            'AppBundle:Main/Announcement:edit.html.twig',
            [
                'form' => $form->createView(),
                'isCourseAnnouncement' => $isCourseAnnouncement,
                'announcement' => $announcement,
            ]
        );
    }

    /**
     * Deletes an Announcement entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_main_announcements_delete")
     * @Method({"GET"})
     *
     * @param Request      $request
     * @param Announcement $announcement
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Announcement $announcement)
    {
        //$this->denyAccessUnlessGranted(HomeworkVoter::DELETE, $homework);

        $flashBag =  $this->get('session')->getFlashBag();
        $flashBag->set(
            'success',
            $this
                ->get('translator')
                ->trans('success.announcement.delete.from_edit', [], 'flashes')
        );


        $course = $announcement->getCourse();

        $em = $this->getDoctrine()->getManager();
        $em->remove($announcement);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        return $this->redirectToRoute(
            'app_main_courses_users_announcements',
            [
                'id' => $course->getId(),
                'userId' => $this->getUser()->getId(),
            ]
        );
    }
}
