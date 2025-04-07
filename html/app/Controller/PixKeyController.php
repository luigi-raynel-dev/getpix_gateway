<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Producer\LoggerProducer;
use App\Grpc\PixKeyClient;
use App\Factory\GrpcClientFactory;
use App\Request\FormPixKeyRequest;

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

  public function store(FormPixKeyRequest $request)
  {
    try {
      $me = $request->getAttribute('me');

      $pixKey = new \Pix\PixKey();
      $pixKey->setKey($request->input('key'));
      $pixKey->setType($request->input('type'));
      $pixKey->setBankISPB($request->input('bankISPB'));
      if ($request->has('belongsTo')) $pixKey->setBelongsTo($request->input('belongsTo'));

      $pixKeyRequest = new \Pix\PixKeyRequest();
      $pixKeyRequest->setPixKey($pixKey);
      $pixKeyRequest->setUserId((string) $me['_id']);

      $grpcResponse = $this->client->createPixKey($pixKeyRequest);

      if (is_array($grpcResponse) && isset($grpcResponse[0])) {
        /**
         * @var \Pix\PixKeyResponse $pixKeyResponse
         */
        $pixKeyResponse = $grpcResponse[0];
        $status = $pixKeyResponse->getStatus() ?? 400;
        return $this->response->json([
          'status' => $status === 201,
          'message' => $pixKeyResponse->getMessage(),
        ])->withStatus($status);
      }

      return $this->response->json([
        'status' => false,
        'message' => "Não foi possível salvar a chave pix."
      ])->withStatus(400);
    } catch (\Throwable $th) {
      return $this->response->json([
        'status' => false,
        'message' => $th->getMessage()
      ])->withStatus(500);
    }
  }

  public function update(string $id, FormPixKeyRequest $request)
  {
    try {
      $me = $request->getAttribute('me');

      $pixKey = new \Pix\PixKey();
      $pixKey->setKey($request->input('key'));
      $pixKey->setType($request->input('type'));
      $pixKey->setBankISPB($request->input('bankISPB'));
      if ($request->has('belongsTo')) $pixKey->setBelongsTo($request->input('belongsTo'));

      $pixKeyRequest = new \Pix\PixKeyRequest();
      $pixKeyRequest->setId($id);
      $pixKeyRequest->setPixKey($pixKey);
      $pixKeyRequest->setUserId((string) $me['_id']);

      $grpcResponse = $this->client->updatePixKey($pixKeyRequest);

      if (is_array($grpcResponse) && isset($grpcResponse[0])) {
        /**
         * @var \Pix\PixKeyResponse $pixKeyResponse
         */
        $pixKeyResponse = $grpcResponse[0];
        $status = $pixKeyResponse->getStatus() ?? 400;
        return $this->response->json([
          'status' => $status === 200,
          'message' => $pixKeyResponse->getMessage()
        ])->withStatus($status);
      }

      return $this->response->json([
        'status' => false,
        'message' => "Não foi possível salvar a chave pix."
      ])->withStatus(400);
    } catch (\Throwable $th) {
      return $this->response->json([
        'status' => false,
        'message' => $th->getMessage()
      ])->withStatus(500);
    }
  }
}
