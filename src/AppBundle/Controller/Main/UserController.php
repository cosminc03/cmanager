<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use AppBundle\Form\User\Main\CreateType;
use AppBundle\Security\UserVoter;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CourseController.
 *
 * @Route("/users")
 */
class UserController extends BaseController
{
    /**
     * Get associate professor component.
     *
     * @Route("/{id}/associate-professor-component", options={"expose"=true}, name="app_main_users_associate_professor_component")
     * @Method("GET")
     *
     * @param User $user
     *
     * @return Response
     */
    public function associateProfessorComponentAction(User $user)
    {
        return $this->render(
            'AppBundle:Main/User:associate_professor_component.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * Edit a User entity.
     *
     * @Route("/{id}/edit", name="app_main_users_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param User    $user
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        $form = $this->createForm(CreateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.user.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_main_users_show',
                [
                    'id' => $user->getId(),
                ])
                ;
        }

        return $this->render(
            'AppBundle:Main/User:edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Display User entity.
     *
     * @Route("/{id}/show", name="app_main_users_show")
     * @Method("GET")
     *
     * @param User $user
     *
     * @return Response
     */
    public function showAction(User $user)
    {
        return $this->render(
            'AppBundle:Main/User:show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * Display User entities with professor and associate roles.
     *
     * @Route("/professors", name="app_main_users_professors")
     * @Method("GET")
     *
     * @return Response
     */
    public function listProfessorsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        $professors = new ArrayCollection();
        /** @var User $user */
        foreach ($users as $user) {
            if (in_array(User::ROLE_PROFESSOR, $user->getRoles()) || in_array(User::ROLE_ASSOCIATE, $user->getRoles())) {
                $professors->add($user);
            }
        }

        return $this->render(
            'AppBundle:Main/User:list_professors.html.twig',
            [
                'professors' => $professors,
            ]
        );
    }

    /**
     * @Route("/notifications", name="app_main_users_notifications")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function notificationsAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if (in_array(User::ROLE_STUDENT, $user->getRoles())) {
            $courses = $user->getSubscribedCourses();
        } else {
            $courses = $user->getLabs();
        }

        $notifications = $this
            ->getDoctrine()
            ->getRepository(Notification::class)
            ->findAllNotifications($courses)
        ;

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $notifications,
            $request->get('page', 1),
            $this->container->getParameter('app.notifications.per_page')
        );

        return $this->render(
            'AppBundle:Main/User:notifications.html.twig',
            [
                'notifications' => $notifications,
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/notifications-number", name="app_main_users_notifications_number")
     * @Method("GET")
     *
     * @return Response
     */
    public function notificationsNumberAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        if (in_array(User::ROLE_STUDENT, $user->getRoles())) {
            $courses = $user->getSubscribedCourses();
        } else {
            $courses = $user->getLabs();
        }

        $notifications = $this
            ->getDoctrine()
            ->getRepository(Notification::class)
            ->findAllNotifications($courses)
        ;

        $notificationsNumber = 0;
        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            if (!$notification->getReaders()->contains($user)) {
                $notificationsNumber += 1;
            }
        }

        return $this->render(
            'AppBundle:Main/Layout:notifications_number.html.twig',
            [
                'notificationsNumber' => $notificationsNumber,
            ]
        );
    }

    /**
     * @Route("/notifications/seen", name="app_main_users_seen_notifications")
     * @Method({"GET"})
     *
     * @param $notifications
     *
     * @return Response
     */
    public function seenNotificationsAction($notifications)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            if (!$notification->getReaders()->contains($this->getUser())) {
                $notification->addReader($this->getUser());
                $em->persist($notification);
            }
        }

        $em->flush();

        return new Response();
    }
}
