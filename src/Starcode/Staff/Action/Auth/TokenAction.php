<?php

namespace Starcode\Staff\Action\Auth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Stream;

class TokenAction
{
    /** @var AuthorizationServer */
    private $authorizationServer;

    /**
     * TokenAction constructor.
     * @param AuthorizationServer $authorizationServer
     */
    public function __construct(AuthorizationServer $authorizationServer)
    {
        $this->authorizationServer = $authorizationServer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        try {
            return $this->authorizationServer->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {

            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getFile(),
                'trace' => $exception->getTrace(),
            ], 500);

        }
    }
}