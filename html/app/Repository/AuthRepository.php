<?php

namespace App\Repository;

use App\Model\User;
use App\Trait\JWTHelper;
use Carbon\Carbon;
use function Hyperf\Support\env;


class AuthRepository implements AuthRepositoryInterface
{
  use JWTHelper;

  public int $http_status = 200;

  protected $jwtSecretKey;
  protected $userCollection;

  public function __construct()
  {
    $this->jwtSecretKey = env('JWT_SECRET_KEY', 'secret');
    $this->userCollection = (new User)->collection;
  }

  public function signIn($request)
  {
    $email = $request->input('email');
    $password = $request->input('password');

    $user = $this->getUserByEmail($email);

    if (!$user) {
      $this->http_status = 401;
      return [
        'status' => false,
        'error' => 'user.not.found',
        'message' => 'Usuário não encontrado'
      ];
    }

    if (password_verify($password, $user->password)) {
      $sub = (string) $user->_id;
      $user = $user->getArrayCopy();
      unset($user['password']);

      return [
        'status' => true,
        'user' => $user,
        'access_token' => $this->getAccessToken($sub),
        'refresh_token' => $this->getRefreshToken($sub),
      ];
    }

    $this->http_status = 401;
    return [
      'status' => false,
      'error' => 'password.not.match',
      'message' => 'Senha incorreta'
    ];
  }

  private function getUserByEmail(string $email)
  {
    return $this->userCollection->findOne(['email' => $email]);
  }

  public function signUp($request)
  {
    try {

      if ($this->getUserByEmail($request->input('email'))) {
        return [
          'status' => false,
          'error' => 'email.exists',
          'message' => 'Email já cadastrado'
        ];
        $this->http_status = 400;
      }

      $user = [
        'firstName' => $request->input('firstName'),
        'lastName' => $request->input('lastName'),
        'email' => $request->input('email'),
        'password' => password_hash($request->input('password'), PASSWORD_BCRYPT)
      ];

      $result = $this->userCollection->insertOne($user);

      $id = $result->getInsertedId();
      if ($id) {
        $this->http_status = 201;
        $user['_id'] = $id;
      }

      return [
        'status' => $id ? true : false,
        'user' => $user,
        'access_token' => $this->getAccessToken($id),
        'refresh_token' => $this->getRefreshToken($id),
      ];
    } catch (\Throwable $th) {
      $this->http_status = 400;
      return [
        'status' => false,
        'error' => 'sign.up.failed',
        'message' => $th->getMessage()
      ];
    }
  }
}
