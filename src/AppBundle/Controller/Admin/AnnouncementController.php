<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Announcement;
use AppBundle\Form\Announcement\CreateType;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Announcement admin controller.
 *
 * @Route("/admin/announcement")
 */
class AnnouncementController extends BaseController
{
    /**
     * Lists all Announcement entities.
     *
     * @Route("/list", name="app_admin_announcement_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $announcements = $em
            ->getRepository(Announcement::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Announcement:list.html.twig',
            [
                'announcements' => $announcements,
            ]
        );
    }

    /**
     * Lists all Announcement entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_announcement_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Announcement::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Announcement entity.
     *
     * @Route("/create", name="app_admin_announcement_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $announcement = new Announcement();
        $form = $this->createForm(CreateType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $announcement->setCreatedBy($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.announcement.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_admin_announcement_show',
                [
                    'id' => $announcement->getId(),
                ])
            ;
        }

        return $this->render(
            'AppBundle:Admin/Announcement:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Announcement entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_announcement_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request          $request
     * @param Announcement     $announcement
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Announcement $announcement)
    {
        $form = $this->createForm(CreateType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $announcement->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($announcement);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.announcement.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_admin_announcement_show',
                [
                    'id' => $announcement->getId(),
                ])
                ;
        }

        return $this->render(
            'AppBundle:Admin/Announcement:edit.html.twig',
            [
                'id' => $announcement->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays an Announcement entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_announcement_show")
     * @Method({"GET"})
     *
     * @param Announcement $announcement
     *
     * @return Response
     */
    public function showAction(Announcement $announcement)
    {
        return $this->render(
            'AppBundle:Admin/Announcement:show.html.twig',
            [
                'announcement' => $announcement,
            ]
        );
    }

    /**
     * Deletes a Announcement entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="app_admin_announcement_delete")
     * @Method({"GET"})
     *
     * @param Request          $request
     * @param Announcement     $announcement
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Announcement $announcement)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($announcement);
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
                    ->trans('success.announcement.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_announcement_list');
    }
}
