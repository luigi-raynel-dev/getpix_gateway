<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Producer\LoggerProducer;

/**
 * @UserController()
 */
class UserController extends AbstractController
{
  protected ResponseInterface $response;
  protected LoggerProducer $producer;


  public function __construct(ResponseInterface $response, LoggerProducer $producer)
  {
    $this->response = $response;
    $this->producer = $producer;
  }

  public function me(ServerRequestInterface $request)
  {
    $me = $request->getAttribute('me');

    $this->producer->send($me ? "info" : "error", "Get details of my user", $me->getArrayCopy(), 'me');

    return $this->response->json(['me' => $me])->withStatus($me ? 200 : 404);
  }
}
