<?php

declare(strict_types=1);

namespace App\Grpc;

use \Hyperf\GrpcClient\BaseClient;
use Pix\{PixKeyRequest, PixKeyResponse, PixKeyId};

class PixKeyClient extends BaseClient
{
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
