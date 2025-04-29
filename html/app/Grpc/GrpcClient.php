<?php

namespace App\Grpc;

use \Hyperf\GrpcClient\BaseClient;

class GrpcClient extends BaseClient
{
  protected function grpcRequest(string $method, $request, string $responseClass)
  {
    return $this->_simpleRequest(
      $method,
      $request,
      [$responseClass, 'decode']
    );
  }
}
