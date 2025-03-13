<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Trait\JWTHelper;
use App\Model\User;


class AuthMiddleware implements MiddlewareInterface
{
  use JWTHelper;

  /**
   * @var ContainerInterface
   */
  protected $container;

  /**
   * @var RequestInterface
   */
  protected $request;

  /**
   * @var HttpResponse
   */
  protected $response;

  public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
  {
    $this->container = $container;
    $this->response = $response;
    $this->request = $request;
  }

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    $token = $this->request->getHeaderLine('Authorization');

    if (!$token) {
      return $this->response->json([
        'status' => false,
        'message' => 'Token de acesso ausente.',
        'error' => 'unauthorized'
      ])->withStatus(401);
    }

    $token = str_replace('Bearer ', '', $token);
    $decoded = $this->decodeToken($token);

    if (!$decoded or $decoded->aud !== 'access') {
      return $this->response->json([
        'status' => false,
        'message' => 'Token de acesso inválido ou expirado.',
        'error' => 'unauthorized',
        'reason' => $this->getError()
      ])->withStatus(401);
    }

    $me = (new User)->findById($decoded->sub);

    if (!$me) {
      return $this->response->json([
        'status' => false,
        'message' => 'Usuário não encontrado.',
        'error' => 'unauthorized'
      ])->withStatus(401);
    }

    $request = $request->withAttribute('me', $me);

    return $handler->handle($request);
  }
}
