<?php

declare(strict_types=1);

namespace App\Grpc;

use Pix\{PixKeyRequest, PixKeyResponse, PixKeyId, PixKeyListRequest, PixKeyListResponse, PixKeyShowResponse};

class PixKeyClient extends GrpcClient
{

  public function getPixKeys(PixKeyListRequest $request)
  {
    return $this->grpcRequest(
      '/grpc.pix/getPixKeys',
      $request,
      PixKeyListResponse::class
    );
  }

  public function getPixKey(PixKeyId $request)
  {
    return $this->grpcRequest(
      '/grpc.pix/getPixKey',
      $request,
      PixKeyShowResponse::class
    );
  }

  public function createPixKey(PixKeyRequest $request)
  {
    return $this->grpcRequest(
      '/grpc.pix/createPixKey',
      $request,
      PixKeyResponse::class
    );
  }

  public function updatePixKey(PixKeyRequest $request)
  {
    return $this->grpcRequest(
      '/grpc.pix/updatePixKey',
      $request,
      PixKeyResponse::class
    );
  }

  public function deletePixKey(PixKeyId $request)
  {
    return $this->grpcRequest(
      '/grpc.pix/deletePixKey',
      $request,
      PixKeyResponse::class
    );
  }
}
