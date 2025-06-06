<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Repository\{AuthRepository, AuthRepositoryInterface};

return [
  AuthRepositoryInterface::class => AuthRepository::class,
  Hyperf\Rpc\Context::class => Hyperf\Rpc\Context::class
];
