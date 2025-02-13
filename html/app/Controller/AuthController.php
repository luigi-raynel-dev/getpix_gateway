<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Service\UserService;

/**
 * @AuthController()
 */
class AuthController extends AbstractController
{
  public function signIn(ResponseInterface $response, UserService $service)
  {
    $data = $this->request->all();

    if (!$service->validate('singIn', $data)) return $response->json([
      'message' => 'Login failed',
      'error' => 'validation-failure',
      'errors' => $service->validator->errors()->messages()
    ])->withStatus(422);

    return [
      'message' => 'Login realizado!',
      'data' => $data
    ];
  }

  public function signUp()
  {

    return ['message' => 'Usuário registrado com sucesso!'];
  }

  public function me()
  {
    return ['message' => 'Informações do usuário logado.'];
  }
}
