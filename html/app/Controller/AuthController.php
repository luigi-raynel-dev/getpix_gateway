<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * @AuthController()
 */
class AuthController extends AbstractController
{
  public function signIn()
  {
    $data = $this->request->all();

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
