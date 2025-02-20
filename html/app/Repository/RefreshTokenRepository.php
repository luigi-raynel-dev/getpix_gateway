<?php

declare(strict_types=1);

namespace App\Repository;

use Hyperf\Redis\Redis;
use Hyperf\Di\Annotation\Inject;

class RefreshTokenRepository
{
  #[Inject]
  private Redis $redis;

  public function saveRefreshToken(string $userId, string $refreshToken, int $ttl = 10080): void
  {
    $key = "refresh_token:$userId:$refreshToken";
    $this->redis->setex($key, $ttl * 60, 'valid');
  }

  public function isValidRefreshToken(string $userId, string $refreshToken): bool
  {
    $key = "refresh_token:$userId:$refreshToken";
    return $this->redis->exists($key) > 0;
  }

  public function revokeRefreshToken(string $userId, string $refreshToken): void
  {
    $key = "refresh_token:$userId:$refreshToken";
    $this->redis->del($key);
  }
}
