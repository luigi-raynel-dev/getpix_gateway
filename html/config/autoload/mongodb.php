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

use function Hyperf\Support\env;

return [
  'uri' => env('MONGODB_URI', 'mongodb://localhost:27017'),
  'database' => env('MONGODB_DATABASE', 'getpix'),
  'database_test' => env('MONGODB_DATABASE_TEST', 'getpix_test'),
];
