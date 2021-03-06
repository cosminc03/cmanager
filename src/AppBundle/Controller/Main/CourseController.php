<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Announcement;
use AppBundle\Entity\Course;
use AppBundle\Entity\File;
use AppBundle\Entity\Homework;
use AppBundle\Entity\Module;
use AppBundle\Entity\User;
use AppBundle\Form\Course\Main\CreateType;
use AppBundle\Security\CourseVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\File\Main\CreateType as FileCreateType;

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

        if (in_array(User::ROLE_STUDENT, $this->getUser()->getRoles())) {
            $courses = $em
                ->getRepository(Course::class)
                ->findBySubscription($this->getUser())
            ;
        }

        if (in_array(User::ROLE_PROFESSOR, $this->getUser()->getRoles())) {
            $courses = $em
                ->getRepository(Course::class)
                ->findBy([
                    'author' => $this->getUser(),
                ])
            ;
        }

        return $this->render(
            'AppBundle:Main/Course:list.html.twig',
            [
                'courses' => $courses,
            ]
        );
    }

    /**
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
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Main/Course:explore.html.twig',
            [
                'courses' => $courses,
            ]
        );
    }

    /**
     * List associated courses for professors.
     *
     * @Route("/associated", name="app_main_courses_associated")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function associatedAction()
    {
        $em = $this->getDoctrine()->getManager();

        $courses = $em
            ->getRepository(Course::class)
            ->findByAssociates($this->getUser())
        ;

        return $this->render(
            'AppBundle:Main/Course:associated.html.twig',
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
        $this->denyAccessUnlessGranted(CourseVoter::CREATE, $course);

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
        $this->denyAccessUnlessGranted(CourseVoter::EDIT, $course);

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
     * Display Course entity.
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
        $this->denyAccessUnlessGranted(CourseVoter::DELETE, $course);

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
    public function addAssociateProfessorAction(Request $request, Course $course)
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
    public function associateProfessorsAction(Course $course)
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

    /**
     * Get all course modules for a Course entity.
     *
     * @Route("/{id}/course-modules", options={"expose"=true}, name="app_main_courses_course_modules")
     * @Method({"GET"})
     *
     * @param Course $course
     *
     * @return Response
     */
    public function courseModulesAction(Course $course)
    {
        $em = $this->getDoctrine()->getManager();

        $courseModules = $em
            ->getRepository(Module::class)
            ->findBy([
                'course' => $course,
                'isCourse' => true,
            ])
        ;

        return $this->render(
            'AppBundle:Main/Module:list_courses.html.twig',
            [
                'course' => $course,
                'courseModules' => $courseModules,
                'isCourse' => true,
            ]
        );
    }

    /**
     * Get all seminar modules for a Course entity.
     *
     * @Route("/{id}/seminar-modules", options={"expose"=true}, name="app_main_courses_seminar_modules")
     * @Method({"GET"})
     *
     * @param Course $course
     *
     * @return Response
     */
    public function seminarModulesAction(Course $course)
    {
        $em = $this->getDoctrine()->getManager();

        $seminarModules = $em
            ->getRepository(Module::class)
            ->findBy([
                'course' => $course,
                'isSeminar' => true,
                'author' => $this->getUser(),
            ])
        ;

        return $this->render(
            'AppBundle:Main/Module:list_seminars.html.twig',
            [
                'course' => $course,
                'seminarModules' => $seminarModules,
                'user' => $this->getUser(),
            ]
        );
    }

    /**
     * Subscribe a Course entity.
     *
     * @Route("/{id}/subscribe", options={"expose"=true}, name="app_main_courses_subscribe")
     * @Method({"GET"})
     *
     * @param Course $course
     *
     * @return JsonResponse
     */
    public function subscribeAction(Course $course)
    {
        $em = $this->getDoctrine()->getManager();

        $notifications = $course->getNotifications();
        foreach ($notifications as $notification) {
            if (!$notification->getReaders()->contains($this->getUser())) {
                $notification->addReader($this->getUser());
                $em->persist($notification);
            }
        }

        $course->addSubscriber($this->getUser());
        $em->persist($course);
        $em->flush();

        $message = [
            'subscribed' => 'success',
        ];

        return new JsonResponse($message);
    }

    /**
     * Unsubscribe a Course entity.
     *
     * @Route("/{id}/unsubscribe", options={"expose"=true}, name="app_main_courses_unsubscribe")
     * @Method({"GET"})
     *
     * @param Course $course
     *
     * @return JsonResponse
     */
    public function unsubscribeAction(Course $course)
    {
        $em = $this->getDoctrine()->getManager();

        $course->removeSubscriber($this->getUser());
        $em->persist($course);
        $em->flush();

        $message = [
            'unsubscribed' => 'success',
        ];

        return new JsonResponse($message);
    }

    /**
     * Get profile for an associate professor for a Course entity.
     *
     * @Route("/{id}/users/{userId}/profile", options={"expose"=true}, name="app_main_courses_users_profile")
     * @Method({"GET"})
     *
     * @param Course $course
     * @param int   $userId
     *
     * @return Response
     */
    public function userProfileAction(Course $course, $userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->find($userId)
        ;

        return $this->render(
            'AppBundle:Main/Course:associate_profile.html.twig',
            [
                'course' => $course,
                'user' => $user,
            ]
        );
    }

    /**
     * Get seminars for an associate professor for a Course entity.
     *
     * @Route("/{id}/users/{userId}/seminars", options={"expose"=true}, name="app_main_courses_users_seminars")
     * @Method({"GET"})
     *
     * @param Course $course
     * @param int   $userId
     *
     * @return Response
     */
    public function userSeminarsAction(Course $course, $userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->find($userId)
        ;

        $seminarModules = $em
            ->getRepository(Module::class)
            ->findBy([
                'author' => $user,
                'course' => $course,
                'isSeminar' => true,
            ])
        ;

        return $this->render(
            'AppBundle:Main/Module:list_seminars.html.twig',
            [
                'course' => $course,
                'seminarModules' => $seminarModules,
                'user' => $user,
            ]
        );
    }

    /**
     * Get homework for a Course entity.
     *
     * @Route("/{id}/homework", options={"expose"=true}, name="app_main_courses_list_homework")
     * @Method({"GET"})
     *
     * @param Course $course
     *
     * @return Response
     */
    public function listHomeworkAction(Course $course)
    {
        $em = $this->getDoctrine()->getManager();

        $homework = $em
            ->getRepository(Homework::class)
            ->findBy([
                'course' => $course,
                'author' => $course->getAuthor(),
                'isCourseHomework' => true,
            ])
        ;

        return $this->render(
            'AppBundle:Main/Homework:list.html.twig',
            [
                'course' => $course,
                'homework' => $homework,
            ]
        );
    }

    /**
     * Show one homework for a Course entity.
     *
     * @Route("/{id}/homework/{homeworkId}", options={"expose"=true}, name="app_main_courses_show_homework")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Course  $course
     *
     * @return Response
     */
    public function showHomeworkAction(Request $request, Course $course, $homeworkId)
    {
        $em = $this->getDoctrine()->getManager();
        $homework = $em
            ->getRepository(Homework::class)
            ->find($homeworkId)
        ;

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
                'app_main_courses_show_homework',
                [
                    'id' => $course->getId(),
                    'homeworkId' => $homeworkId,
                ]
            );
        }

        return $this->render(
            'AppBundle:Main/Homework:show_course_homework.html.twig',
            [
                'course' => $course,
                'homework' => $homework,
                'fileUploadForm' => $fileUploadForm->createView(),
            ]
        );
    }

    /**
     * Get homework for an associate professor for a Course entity.
     *
     * @Route("/{id}/users/{userId}/homework", options={"expose"=true}, name="app_main_courses_users_homework")
     * @Method({"GET"})
     *
     * @param Course $course
     * @param int   $userId
     *
     * @return Response
     */
    public function userHomeworkAction(Course $course, $userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->find($userId)
        ;

        $homework = $em
            ->getRepository(Homework::class)
            ->findBy([
                'author' => $user,
                'course' => $course,
                'isCourseHomework' => false,
            ])
        ;

        $hideAuxNav = false;
        if ($course->getAuthor() == $this->getUser()) {
            $hideAuxNav = true;
        }

        return $this->render(
            'AppBundle:Main/Homework:list_user.html.twig',
            [
                'course' => $course,
                'homework' => $homework,
                'user' => $user,
                'hideAuxNav' => $hideAuxNav,
            ]
        );
    }

    /**
     * Get announcements for a Course entity.
     *
     * @Route("/{id}/announcements", options={"expose"=true}, name="app_main_courses_list_announcements")
     * @Method({"GET"})
     *
     * @param Course $course
     *
     * @return Response
     */
    public function listAnnouncementsAction(Course $course)
    {
        $em = $this->getDoctrine()->getManager();

        $announcements = $em
            ->getRepository(Announcement::class)
            ->findBy([
                'course' => $course,
                'createdBy' => $course->getAuthor(),
                'isCourseAnnouncement' => true,
            ])
        ;

        return $this->render(
            'AppBundle:Main/Announcement:list.html.twig',
            [
                'course' => $course,
                'announcements' => $announcements,
            ]
        );
    }

    /**
     * Get announcements for an associate professor for a Course entity.
     *
     * @Route("/{id}/users/{userId}/announcements", options={"expose"=true}, name="app_main_courses_users_announcements")
     * @Method({"GET"})
     *
     * @param Course $course
     * @param int   $userId
     *
     * @return Response
     */
    public function usersAnnouncementsAction(Course $course, $userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->find($userId)
        ;

        $announcements = $em
            ->getRepository(Announcement::class)
            ->findBy([
                'createdBy' => $user,
                'course' => $course,
                'isCourseAnnouncement' => false,
            ])
        ;

        $hideAuxNav = false;
        if ($course->getAuthor() == $this->getUser()) {
            $hideAuxNav = true;
        }

        return $this->render(
            'AppBundle:Main/Announcement:list_user.html.twig',
            [
                'course' => $course,
                'announcements' => $announcements,
                'user' => $user,
                'hideAuxNav' => $hideAuxNav,
            ]
        );
    }

}
