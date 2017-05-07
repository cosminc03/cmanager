<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Module;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController.
 *
 * @Route("/posts")
 */
class PostController extends BaseController
{
    /**
     * Create a new Post entity.
     *
     * @Route("/create", options={"expose"=true}, name="app_main_posts_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|JsonResponse
     */
    public function createAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->all();

            $module = $em
                ->getRepository(Module::class)
                ->find($data['moduleId'])
            ;

            $user = $em
                ->getRepository(User::class)
                ->find($data['userId'])
            ;

            if ($user && $module) {
                $post = new Post();
                $post
                    ->setModule($module)
                    ->setCreatedBy($user)
                    ->setText($data['comment'])
                ;

                $em->persist($post);
                $em->flush();

                return $this->createApiResponse($post, Response::HTTP_CREATED);
            }

            $message = [
                'created' => 'failed',
            ];

            return new JsonResponse($message);
        }

        return new Response();
    }
}
