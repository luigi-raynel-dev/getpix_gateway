<?php

namespace App\Producer;

use Hyperf\Kafka\Producer;
use function Hyperf\Support\env;

class LoggerProducer
{
  protected Producer $producer;

  public function __construct(Producer $producer)
  {
    $this->producer = $producer;
  }

  public function send(string $level, string $message, array $context = [], ?string $key = null)
  {

    $data = json_encode([
      'level' => $level,
      'message' => $message,
      'context' => $context,
      'timestamp' => date('Y-m-d H:i:s'),
    ]);

    $this->producer->sendAsync(env('KAFKA_LOGS_TOPIC', 'getpix.logs'), $data, $key);
  }
}
