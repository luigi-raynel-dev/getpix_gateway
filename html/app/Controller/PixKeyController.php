<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Producer\LoggerProducer;
use App\Grpc\PixKeyClient;

/**
 * @PixKeyController()
 */
class PixKeyController extends AbstractController
{
  protected ResponseInterface $response;
  protected LoggerProducer $producer;


  public function __construct(ResponseInterface $response, LoggerProducer $producer)
  {
    $this->response = $response;
    $this->producer = $producer;
  }

  public function store(ServerRequestInterface $request)
  {
    $client = new PixKeyClient('getpix_pix:9503', [
      'credentials' => null,
    ]);

    $pixKey = new \Pix\PixKey();
    $pixKey->setKey('hyperf@email.com');
    $pixKey->setType('email');
    $pixKey->setBankISPB('20018183');

    $pixKeyRequest = new \Pix\PixKeyRequest();
    $pixKeyRequest->setPixKey($pixKey);
    $pixKeyRequest->setUserId('5d1s54d5s4d4sd54s5d4s');

    /**
     * @var \Pix\PixKeyResponse
     */
    $pixKeyResponse = $client->CreatePixKey($pixKeyRequest);

    return $this->response->json(['message' => $pixKeyResponse->getMessage()])->withStatus($pixKeyResponse->getStatus() ?? 400);
  }
}
