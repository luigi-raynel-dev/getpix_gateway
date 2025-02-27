<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Repository\AuthRepository;
use App\Producer\LoggerProducer;
use App\Request\{SignInRequest, SignUpRequest, RefreshTokenRequest};

/**
 * @AuthController()
 */
class AuthController extends AbstractController
{

  private $repository;
  protected ResponseInterface $response;
  protected LoggerProducer $producer;


  public function __construct(ResponseInterface $response, AuthRepository $repository, LoggerProducer $producer)
  {
    $this->repository = $repository;
    $this->response = $response;
    $this->producer = $producer;
  }

  public function signIn(SignInRequest $request)
  {
    $result = $this->repository->signIn($request);

    return $this->response->json($result)->withStatus($this->repository->http_status);
  }

  public function signUp(SignUpRequest $request)
  {
    $result = $this->repository->signUp($request);

    return $this->response->json($result)->withStatus($this->repository->http_status);
  }

  public function refreshToken(RefreshTokenRequest $request)
  {
    $result = $this->repository->refreshToken($request);

    return $this->response->json($result)->withStatus($this->repository->http_status);
  }
}
