<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Producer\LoggerProducer;
use App\Grpc\PixKeyClient;
use App\Factory\GrpcClientFactory;
use App\Request\CreatePixKeyRequest;

/**
 * @PixKeyController()
 */
class PixKeyController extends AbstractController
{
  protected ResponseInterface $response;
  protected LoggerProducer $producer;
  private PixKeyClient $client;


  public function __construct(ResponseInterface $response, LoggerProducer $producer, GrpcClientFactory $grpcClientFactory)
  {
    $this->response = $response;
    $this->producer = $producer;
    $this->client = $grpcClientFactory->make('grpc', PixKeyClient::class);
  }

  public function store(CreatePixKeyRequest $request)
  {
    $me = $request->getAttribute('me');

    $pixKey = new \Pix\PixKey();
    $pixKey->setKey($request->input('key'));
    $pixKey->setType($request->input('type'));
    $pixKey->setBankISPB($request->input('bankISPB'));
    if ($request->has('belongsTo')) $pixKey->setBelongsTo($request->input('belongsTo'));

    $pixKeyRequest = new \Pix\PixKeyRequest();
    $pixKeyRequest->setPixKey($pixKey);
    $pixKeyRequest->setUserId((string) $me['_id']);

    $grpcResponse = $this->client->CreatePixKey($pixKeyRequest);

    if (is_array($grpcResponse) && isset($grpcResponse[0])) {
      /**
       * @var \Pix\PixKeyResponse $pixKeyResponse
       */
      $pixKeyResponse = $grpcResponse[0];
      return $this->response->json(['message' => $pixKeyResponse->getMessage()])
        ->withStatus($pixKeyResponse->getStatus() ?? 400);
    }

    return $this->response->json(['message' => "error"])->withStatus(400);
  }
}
