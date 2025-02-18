<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AuthRepository;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Request\{SignInRequest, SignUpRequest};

/**
 * @AuthController()
 */
class AuthController extends AbstractController
{

  private $repository;
  protected ResponseInterface $response;


  public function __construct(ResponseInterface $response, AuthRepository $repository)
  {
    $this->repository = $repository;
    $this->response = $response;
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

  public function me()
  {
    return ['message' => 'Informações do usuário logado.'];
  }
}
