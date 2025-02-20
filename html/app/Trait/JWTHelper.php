<?php

declare(strict_types=1);

namespace App\Trait;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use function Hyperf\Support\env;

trait JWTHelper
{

  public function generateToken(int|string $sub, int $minutes_to_expire, string $aud, $extra_payload = [])
  {
    $payload = [
      'sub' => $sub,
      'iat' => Carbon::now()->timestamp,
      'exp' => Carbon::now()->addMinutes($minutes_to_expire)->timestamp,
      'aud' => $aud
    ];

    $mergedPayload = array_merge($payload, $extra_payload);
    return JWT::encode($mergedPayload, $this->jwtSecretKey, 'HS256');
  }

  public function getAccessToken(string|int $sub, int $minutes_to_expire = null, $extra_payload = [])
  {
    $exp = $minutes_to_expire ?? (int) env('JWT_ACCESS_EXP', "60");
    return $this->generateToken($sub, $exp, 'access', $extra_payload);
  }

  public function getRefreshToken(string|int $sub, int $minutes_to_expire = null, $extra_payload = [])
  {
    $exp = $minutes_to_expire ?? (int) env('JWT_REFRESH_EXP', "10080");
    return $this->generateToken($sub, $exp, 'refresh', $extra_payload);
  }

  public function decodeToken(string $token)
  {
    try {
      return JWT::decode($token, new Key($this->jwtSecretKey, 'HS256'));
    } catch (\Throwable $th) {
      print_r($th);
      return null;
    }
  }
}
