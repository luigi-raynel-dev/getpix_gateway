<?php

declare(strict_types=1);

namespace App\Factory;

use Hyperf\Contract\ConfigInterface;
use RuntimeException;

class GrpcClientFactory
{
  private ConfigInterface $config;

  public function __construct(ConfigInterface $config)
  {
    $this->config = $config;
  }

  public function make(string $serviceName, string $clientClass)
  {
    $servers = $this->config->get('server.servers', []);

    foreach ($servers as $server) {
      if ($server['name'] === $serviceName) {
        $host = $server['host'] ?? 'localhost';
        $port = $server['port'] ?? 9503;

        return new $clientClass("{$host}:{$port}", [
          'credentials' => null,
        ]);
      }
    }

    throw new RuntimeException("Serviço gRPC '{$serviceName}' não encontrado.");
  }
}
