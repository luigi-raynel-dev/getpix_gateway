<?php

declare(strict_types=1);

namespace App\Grpc;

use \Hyperf\GrpcClient\BaseClient;
use Pix\PixKeyRequest;
use Pix\PixKeyResponse;

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
}
