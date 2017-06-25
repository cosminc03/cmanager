<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\User\Main\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default admin controller.
 */
class DefaultController extends BaseController
{
    /**
     * @Route("/home", name="app_admin_default_homepage")
     */
    public function adminAction(Request $request)
    {
        return $this->render('AppBundle:Admin:home.html.twig');
    }

    /**
     * @param $token
     *
     * @Route("/activate-account/{token}", name="app_default_activate_account")
     *
     * @return Response
     */
    public function activateAccountAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->findOneBy([
                'confirmationToken' => $token
            ])
        ;

        if ($user) {
            $user
                ->setEnabled(true)
                ->setConfirmationToken(null)
            ;

            $em->persist($user);
            $em->flush();
        }

        return $this->render('AppBundle:Main/Layout:account_activated.html.twig');
    }

    /**
     * @param Request $request
     * @param         $token
     *
     * @Route("/reset-password/{token}", name="app_default_reset_password")
     *
     * @return Response
     */
    public function resetPasswordAction(Request $request, $token)
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');
            $em = $this->getDoctrine()->getManager();
            $user = $em
                ->getRepository(User::class)
                ->findOneBy([
                    'resetToken' => $token,
                ])
            ;
            $user->setResetToken(null);
            $user->setPlainPassword($form->get('plainPassword')->getData());
            $userManager->updateUser($user, true);

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.user.reset_password', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_main_security_login');
        }

        return $this->render(
            'AppBundle:Main/Layout:reset_password.html.twig',
            [
                'form' => $form->createView(),
                'token' => $token,
            ]
        );
    }
}
