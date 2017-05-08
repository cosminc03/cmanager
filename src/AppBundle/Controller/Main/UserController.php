<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\User;
use AppBundle\Form\User\Main\CreateType;
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
}
