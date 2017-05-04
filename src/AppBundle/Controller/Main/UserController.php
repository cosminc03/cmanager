<?php

namespace AppBundle\Controller\Main;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
}
