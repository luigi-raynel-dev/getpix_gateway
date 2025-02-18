<?php

namespace App\Repository;

use App\Model\User;
use App\Request\SignInRequest;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use function Hyperf\Support\env;


class AuthRepository implements AuthRepositoryInterface
{
  public int $http_status = 200;

  protected $jwtSecretKey;
  protected $userCollection;


  public function __construct()
  {
    $this->jwtSecretKey = env('JWT_SECRET_KEY');
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
      $tokenPayload = [
        'sub' => (string) $user->_id,
        'iat' => Carbon::now()->timestamp,
        'exp' => Carbon::now()->addHours(24)->timestamp,
      ];

      $token = JWT::encode($tokenPayload, $this->jwtSecretKey, 'HS256');

      $user = $user->getArrayCopy();
      unset($user['password']);

      return [
        'status' => true,
        'access_token' => $token,
        'user' => $user,
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

      $result = $this->userCollection->insertOne([
        'firstName' => $request->input('firstName'),
        'lastName' => $request->input('lastName'),
        'email' => $request->input('email'),
        'password' => password_hash($request->input('password'), PASSWORD_BCRYPT)
      ]);

      $id = $result->getInsertedId();
      if ($id) $this->http_status = 201;
      return [
        'status' => $id ? true : false,
        'id' => $id
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
