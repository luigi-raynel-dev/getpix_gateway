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

    $level = $result['status'] ? 'info' : 'error';
    $message = $result['status'] ? 'User signed in' : 'User signed in error';
    $context = [
      'email' => $request->input('email'),
      'user' => array_key_exists('user', $result) ? $result['user'] : [],
      'error' => array_key_exists('error', $result) ? $result['error'] : null,
      'message' => array_key_exists('message', $result) ? $result['message'] : null,
    ];

    $this->producer->send($level, $message, $context, 'sign-in');

    return $this->response->json($result)->withStatus($this->repository->http_status);
  }

  public function signUp(SignUpRequest $request)
  {
    $result = $this->repository->signUp($request);

    $level = $result['status'] ? 'info' : 'error';
    $message = $result['status'] ? 'User signed up' : 'User signed up error';
    $context = [
      'email' => $request->input('email'),
      'user' => array_key_exists('user', $result) ? $result['user'] : [],
      'error' => array_key_exists('error', $result) ? $result['error'] : null,
      'message' => array_key_exists('message', $result) ? $result['message'] : null,
    ];

    $this->producer->send($level, $message, $context, 'sign-up');

    return $this->response->json($result)->withStatus($this->repository->http_status);
  }

  public function refreshToken(RefreshTokenRequest $request)
  {
    $result = $this->repository->refreshToken($request);

    $level = $result['status'] ? 'info' : 'error';
    $message = $result['status'] ? 'User refreshed token' : 'User refreshed token error';

    $context = [
      'error' => array_key_exists('error', $result) ? $result['error'] : null,
      'message' => array_key_exists('message', $result) ? $result['message'] : null,
    ];

    $this->producer->send($level, $message, $context, 'refresh-token');

    return $this->response->json($result)->withStatus($this->repository->http_status);
  }
}
