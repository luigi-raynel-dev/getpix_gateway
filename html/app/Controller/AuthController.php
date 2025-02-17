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

    // $data = $this->request->all();

    // if (!$service->validate('singIn', $data)) return $response->json([
    //   'message' => 'Login failed',
    //   'error' => 'validation-failure',
    //   'errors' => $service->validator->errors()->messages()
    // ])->withStatus(422);

    // return [
    //   'message' => 'Login realizado!',
    //   'data' => $data
    // ];
  }

  public function signUp(SignUpRequest $request)
  {
    return $this->repository->signUp($request);
  }

  public function me()
  {
    return ['message' => 'Informações do usuário logado.'];
  }
}
