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
  protected $jwtSecretKey;

  public function __construct()
  {
    $this->jwtSecretKey = env('JWT_SECRET_KEY');
  }

  public function signIn($request)
  {
    $email = $request->input('email');
    $password = $request->input('password');

    $user = $this->getUserByEmail($email);

    if (!$user) {
      return ['error' => 'Usuário não encontrado'];
    }

    // if (password_verify($password, $user->password)) {
    //   $tokenPayload = [
    //     'uuid' => $user->uuid,
    //     'email' => $user->email,
    //     'iat' => time(),
    //   ];

    //   $token = JWT::encode($tokenPayload, $this->jwtSecretKey, 'HS256');

    //   return ['token' => $token];
    // }

    return ['error' => 'Senha incorreta'];
  }

  private function getUserByEmail($email)
  {
    return [];
    // return new User;
    // return User::where('email', $email)->first();
  }

  public function signUp($request)
  {
    $result = (new User)->collection->insertOne([
      'firstName' => $request->input('firstName'),
      'lastName' => $request->input('firstName'),
      'email' => $request->input('email'),
      'password' => password_hash($request->input('password'), PASSWORD_BCRYPT)
    ]);

    return $result;
  }
}
