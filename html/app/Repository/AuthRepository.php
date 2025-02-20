<?php

namespace App\Repository;

use App\Model\User;
use App\Trait\JWTHelper;
use function Hyperf\Support\env;


class AuthRepository implements AuthRepositoryInterface
{
  use JWTHelper;

  public int $http_status = 200;

  protected $jwtSecretKey;
  protected $userCollection;
  protected RefreshTokenRepository $refreshTokenRepository;

  public function __construct()
  {
    $this->jwtSecretKey = env('JWT_SECRET_KEY', 'secret');
    $this->userCollection = (new User)->collection;
    $this->refreshTokenRepository = new RefreshTokenRepository();
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
        'refresh_token' => $this->setAndGetRefreshToken($sub),
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

        return [
          'status' => true,
          'user' => $user,
          'access_token' => $this->getAccessToken($id),
          'refresh_token' => $this->setAndGetRefreshToken($id),
        ];
      }

      $this->http_status = 400;
      return [
        'status' => false,
        'error' => 'sign.up.failed',
        'message' => 'Erro ao cadastrar usuário'
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

  public function refreshToken($request)
  {
    try {
      $refreshToken = $request->input('refresh_token');
      $decoded = $this->decodeToken($refreshToken);

      if (!$decoded or $decoded->aud !== 'refresh' or !$this->refreshTokenRepository->isValidRefreshToken($decoded->sub, $refreshToken)) {
        $this->http_status = 401;
        return [
          'status' => false,
          'error' => 'invalid.token',
          'message' => 'Token inválido ou expirado'
        ];
      }

      $this->refreshTokenRepository->revokeRefreshToken($decoded->sub, $refreshToken);

      return [
        'status' => true,
        'access_token' => $this->getAccessToken($decoded->sub),
        'refresh_token' => $this->setAndGetRefreshToken($decoded->sub)
      ];
    } catch (\Throwable $th) {
      $this->http_status = 400;
      return [
        'status' => false,
        'error' => 'refresh.token.failed',
        'message' => $th->getMessage()
      ];
    }
  }

  private function setAndGetRefreshToken(string $userId)
  {
    $refreshToken = $this->getRefreshToken($userId);
    $ttl = (int) env('JWT_REFRESH_EXP', "10080");
    $this->refreshTokenRepository->saveRefreshToken($userId, $refreshToken, $ttl);

    return $refreshToken;
  }
}
