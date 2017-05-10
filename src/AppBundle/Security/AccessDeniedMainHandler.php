<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedMainHandler implements AccessDeniedHandlerInterface
{
    private $twig;

    /**
     * AccessDeniedMainHandler constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $response = new Response('', 403);
        $response->setContent($this->twig->render('AppBundle:Main/Layout:access_denied.html.twig'));

        return $response;
    }
}
