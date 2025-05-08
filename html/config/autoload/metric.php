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

use Hyperf\Metric\Adapter\Prometheus\Constants;
use Hyperf\Metric\Adapter\Prometheus\MetricFactory;

use function Hyperf\Support\env;

return [
    // To disable hyperf/metric temporarily, set default driver to noop.
    'default' => env('METRIC_DRIVER', 'prometheus'),
    'use_standalone_process' => env('METRIC_USE_STANDALONE_PROCESS', true),
    'enable_default_metric' => env('METRIC_ENABLE_DEFAULT_METRIC', true),
    'enable_command_metric' => env('METRIC_ENABLE_COMMAND_METRIC', true),
    'default_metric_interval' => env('DEFAULT_METRIC_INTERVAL', 5),
    // only available when use_standalone_process is true
    'buffer_interval' => env('METRIC_BUFFER_INTERVAL', 5),
    'buffer_size' => env('METRIC_BUFFER_SIZE', 200),
    'metric' => [
        'prometheus' => [
            'driver' => MetricFactory::class,
            'mode' => Constants::SCRAPE_MODE,
            'namespace' => env('APP_NAME', 'getpix_app'),
            'redis_config' => env('PROMETHEUS_REDIS_CONFIG', 'default'),
            'redis_prefix' => env('PROMETHEUS_REDIS_PREFIX', 'prometheus:'),
            'redis_gather_key_suffix' => env('PROMETHEUS_REDIS_GATHER_KEY_SUFFIX', ':metric_keys'),
            'scrape_host' => env('PROMETHEUS_SCRAPE_HOST', '0.0.0.0'),
            'scrape_port' => env('PROMETHEUS_SCRAPE_PORT', '9510'),
            'scrape_path' => env('PROMETHEUS_SCRAPE_PATH', '/metrics'),
            'push_host' => env('PROMETHEUS_PUSH_HOST', '0.0.0.0'),
            'push_port' => env('PROMETHEUS_PUSH_PORT', '9091'),
            'push_interval' => env('PROMETHEUS_PUSH_INTERVAL', 5),
        ]
    ],
];
