<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
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

  public function index(RequestInterface $request)
  {
    try {
      $me = $request->getAttribute('me');

      $pixKeyRequest = new \Pix\PixKeyListRequest();
      $pixKeyRequest->setUserId((string) $me['_id']);

      $grpcResponse = $this->client->getPixKeys($pixKeyRequest);

      if (is_array($grpcResponse) && isset($grpcResponse[0])) {
        $level = 'info';
        $message = 'List pix keys successfully';
        $context = [
          'user' => $me
        ];

        $this->producer->send($level, $message, $context, 'list-pix-keys');

        /**
         * @var \Pix\PixKeyListResponse $pixKeyResponse
         */
        $pixKeyResponse = $grpcResponse[0];
        return $this->response->json([
          'pixKeys' => json_decode($pixKeyResponse->getPixKeys())
        ]);
      }

      $level = 'error';
      $message = 'Can not list pix keys';
      $context = [
        'user' => $me
      ];

      $this->producer->send($level, $message, $context, 'list-pix-keys');

      return $this->response->json([
        'status' => false,
        'message' => "Não foi possível listar as chaves pix."
      ])->withStatus(400);
    } catch (\Throwable $th) {
      $level = 'error';
      $message = 'Can not list pix keys';
      $context = [
        'user' => $me,
        'error' => $th->getMessage()
      ];

      $this->producer->send($level, $message, $context, 'list-pix-keys');

      return $this->response->json([
        'status' => false,
        'message' => $th->getMessage()
      ])->withStatus(500);
    }
  }

  public function show(string $id, RequestInterface $request)
  {
    try {
      $me = $request->getAttribute('me');

      $pixKeyRequest = new \Pix\PixKeyId();
      $pixKeyRequest->setId($id);
      $pixKeyRequest->setUserId((string) $me['_id']);

      $grpcResponse = $this->client->getPixKey($pixKeyRequest);

      if (is_array($grpcResponse) && isset($grpcResponse[0])) {


        /**
         * @var \Pix\PixKeyShowResponse $pixKeyResponse
         */
        $pixKeyResponse = $grpcResponse[0];
        $pixKey = $pixKeyResponse->getPixKey();
        $pixKey = $pixKey ? json_decode($pixKeyResponse->getPixKey()) : null;

        $level = $pixKey ? 'info' : 'error';
        $message = $pixKey ? 'Get pix key successfully' : 'Pix key not found';
        $context = [
          'user' => $me,
          'id' => $id
        ];
        $this->producer->send($level, $message, $context, 'get-pix-key');

        return $this->response->json([
          'pixKey' => $pixKey,
        ])->withStatus($pixKey ? 200 : 404);
      }

      $level = 'error';
      $message = 'Can not get pix key';
      $context = [
        'user' => $me,
        'id' => $id
      ];
      $this->producer->send($level, $message, $context, 'get-pix-key');

      return $this->response->json([
        'status' => false,
        'message' => "Não foi possível buscar a chave pix."
      ])->withStatus(400);
    } catch (\Throwable $th) {
      $level = 'error';
      $message = 'Can not get pix key';
      $context = [
        'user' => $me,
        'id' => $id,
        'error' => $th->getMessage()
      ];
      $this->producer->send($level, $message, $context, 'get-pix-key');

      return $this->response->json([
        'status' => false,
        'message' => $th->getMessage()
      ])->withStatus(500);
    }
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

        $level = $status == 201 ? 'info' : 'error';
        $message = $status == 201 ? 'Pix key created successfully' : 'Pix key not created';
        $context = [
          'user' => $me
        ];
        $this->producer->send($level, $message, $context, 'create-pix-key');

        return $this->response->json([
          'status' => $status == 201,
          'message' => $pixKeyResponse->getMessage(),
        ])->withStatus($status);
      }

      $level = 'error';
      $message = 'Can not create pix key';
      $context = [
        'user' => $me
      ];
      $this->producer->send($level, $message, $context, 'create-pix-key');

      return $this->response->json([
        'status' => false,
        'message' => "Não foi possível salvar a chave pix."
      ])->withStatus(400);
    } catch (\Throwable $th) {
      $level = 'error';
      $message = 'Can not create pix key';
      $context = [
        'user' => $me,
        'error' => $th->getMessage()
      ];
      $this->producer->send($level, $message, $context, 'create-pix-key');

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

        $level = $status == 200 ? 'info' : 'error';
        $message = $status == 200 ? 'Pix key updated successfully' : 'Pix key not updated';
        $context = [
          'user' => $me,
          'id' => $id
        ];
        $this->producer->send($level, $message, $context, 'update-pix-key');

        return $this->response->json([
          'status' => $status == 200,
          'message' => $pixKeyResponse->getMessage()
        ])->withStatus($status);
      }

      $level = 'error';
      $message = 'Can not update pix key';
      $context = [
        'user' => $me,
        'id' => $id
      ];
      $this->producer->send($level, $message, $context, 'update-pix-key');

      return $this->response->json([
        'status' => false,
        'message' => "Não foi possível salvar a chave pix."
      ])->withStatus(400);
    } catch (\Throwable $th) {
      $level = 'error';
      $message = 'Can not update pix key';
      $context = [
        'user' => $me,
        'id' => $id,
        'error' => $th->getMessage()
      ];
      $this->producer->send($level, $message, $context, 'update-pix-key');

      return $this->response->json([
        'status' => false,
        'message' => $th->getMessage()
      ])->withStatus(500);
    }
  }

  public function destroy(string $id, RequestInterface $request)
  {
    try {
      $me = $request->getAttribute('me');

      $pixKeyRequest = new \Pix\PixKeyId();
      $pixKeyRequest->setId($id);
      $pixKeyRequest->setUserId((string) $me['_id']);

      $grpcResponse = $this->client->deletePixKey($pixKeyRequest);

      if (is_array($grpcResponse) && isset($grpcResponse[0])) {
        /**
         * @var \Pix\PixKeyResponse $pixKeyResponse
         */
        $pixKeyResponse = $grpcResponse[0];
        $status = $pixKeyResponse->getStatus() ?? 400;

        $level = $status == 200 ? 'info' : 'error';
        $message = $status == 200 ? 'Pix key deleted successfully' : 'Pix key not deleted';
        $context = [
          'user' => $me,
          'id' => $id
        ];
        $this->producer->send($level, $message, $context, 'delete-pix-key');

        return $this->response->json([
          'status' => $status == 200,
          'message' => $pixKeyResponse->getMessage()
        ])->withStatus($status);
      }

      $level = 'error';
      $message = 'Can not delete pix key';
      $context = [
        'user' => $me,
        'id' => $id
      ];
      $this->producer->send($level, $message, $context, 'delete-pix-key');

      return $this->response->json([
        'status' => false,
        'message' => "Não foi possível excluir a chave pix."
      ])->withStatus(400);
    } catch (\Throwable $th) {
      $level = 'error';
      $message = 'Can not delete pix key';
      $context = [
        'user' => $me,
        'id' => $id,
        'error' => $th->getMessage()
      ];
      $this->producer->send($level, $message, $context, 'delete-pix-key');

      return $this->response->json([
        'status' => false,
        'message' => $th->getMessage()
      ])->withStatus(500);
    }
  }
}
