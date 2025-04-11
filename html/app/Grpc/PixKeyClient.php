<?php

declare(strict_types=1);

namespace App\Grpc;

use \Hyperf\GrpcClient\BaseClient;
use Pix\{PixKeyRequest, PixKeyResponse, PixKeyId, PixKeyListRequest, PixKeyListResponse, PixKeyShowResponse};

class PixKeyClient extends BaseClient
{
  public function getPixKeys(PixKeyListRequest $request)
  {
    return $this->_simpleRequest(
      '/grpc.pix/getPixKeys',
      $request,
      [PixKeyListResponse::class, 'decode']
    );
  }

  public function getPixKey(PixKeyId $request)
  {
    return $this->_simpleRequest(
      '/grpc.pix/getPixKey',
      $request,
      [PixKeyShowResponse::class, 'decode']
    );
  }

  public function createPixKey(PixKeyRequest $request)
  {
    return $this->_simpleRequest(
      '/grpc.pix/createPixKey',
      $request,
      [PixKeyResponse::class, 'decode']
    );
  }

  public function updatePixKey(PixKeyRequest $request)
  {
    return $this->_simpleRequest(
      '/grpc.pix/updatePixKey',
      $request,
      [PixKeyResponse::class, 'decode']
    );
  }

  public function deletePixKey(PixKeyId $request)
  {
    return $this->_simpleRequest(
      '/grpc.pix/deletePixKey',
      $request,
      [PixKeyResponse::class, 'decode']
    );
  }
}
