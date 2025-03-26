<?php

declare(strict_types=1);

namespace App\Grpc;

use \Hyperf\GrpcClient\BaseClient;
use Pix\PixKeyRequest;
use Pix\PixKeyResponse;

class PixKeyClient extends BaseClient
{
  public function CreatePixKey(PixKeyRequest $request)
  {
    return $this->_simpleRequest(
      '/grpc.pix/createPixKey',
      $request,
      [PixKeyResponse::class, 'decode']
    );
  }
}
