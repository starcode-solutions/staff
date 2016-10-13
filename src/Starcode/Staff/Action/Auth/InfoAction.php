<?php

namespace Starcode\Staff\Action\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Starcode\Staff\Entity\User;
use Starcode\Staff\Repository\UserRepository;
use Zend\Diactoros\Response\JsonResponse;

class InfoAction
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * InfoAction constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        /** @var User $user */
        $user = $this->userRepository->find($request->getAttribute('oauth_user_id'));
        return new JsonResponse([
            'email' => $user->getEmail(),
            'forename' => $user->getForename(),
            'surname' => $user->getSurname(),
            'scopes' => $request->getAttribute('oauth_scopes'),
        ]);
    }
}