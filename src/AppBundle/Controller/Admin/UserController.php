<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\User\CreateType;
use AppBundle\Controller\BaseController;
use AppBundle\Form\User\UploadXMLType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User admin controller.
 *
 * @Route("/admin/user")
 */
class UserController extends BaseController
{
    /**
     * Lists all User entities.
     *
     * @Route("/list", name="app_admin_user_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $form = $this->createForm(UploadXMLType::class);
        $em = $this->getDoctrine()->getManager();

        $users = $em
            ->getRepository(User::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/User:list.html.twig',
            [
                'users' => $users,
                'form_xml' => $form->createView(),
            ]
        );
    }

    /**
     * Lists all User entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_user_list_filtered")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(User::class, 'username', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="app_admin_user_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $userService = $this->get('app.service.user');
        $user = new User();
        $form = $this->createForm(CreateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $userService->generateUsername($user);
            $password = $userService->randomPassword();

            $user->setUsername($username);
            $user->setPlainPassword($password);
            $user->setConfirmationToken(substr(md5(microtime()), rand(0, 26), 6));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $mailerService = $this->get('app.service.mailer');
            $mailerService->sendEmail(
                'AppBundle:Admin/Email:create_user_account.html.twig',
                'info',
                $user->getEmail(),
                [
                    'token' => $user->getConfirmationToken(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'password' => $password,
                ]
            );

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.user.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_admin_user_show',
                [
                    'id' => $user->getId(),
                ])
                ;
        }

        return $this->render(
            'AppBundle:Admin/User:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_user_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request  $request
     * @param User     $user
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(CreateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
                'app_admin_user_show',
                [
                    'id' => $user->getId(),
                ])
                ;
        }

        return $this->render(
            'AppBundle:Admin/User:edit.html.twig',
            [
                'id' => $user->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays an User entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_user_show")
     * @Method({"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function showAction(User $user)
    {
        return $this->render(
            'AppBundle:Admin/User:show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="app_admin_user_delete")
     * @Method({"GET"})
     *
     * @param Request  $request
     * @param User     $user
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.user.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_user_list');
    }

    /**
     * Uploads an XML file.
     *
     * @Route("/upload-xml", options={"expose"=true}, name="app_admin_user_upload_xml")
     * @Method({"GET", "POST"})
     *
     * @param Request  $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function uploadXmlAction(Request $request)
    {
        $form = $this->createForm(UploadXMLType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump("cucu");die;
        }

        dump("nu este valid");die;
        return new JsonResponse();
    }
}
