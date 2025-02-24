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

namespace HyperfTest\Cases;

use Hyperf\Testing\TestCase;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Context\ApplicationContext;
use MongoDB\{Client, Database, Collection};

/**
 * @internal
 * @coversNothing
 */
class AuthTest extends TestCase
{

    private Client $client;
    private Database $db;
    private Collection $collection;

    protected function setUp(): void
    {
        parent::setUp();
        $container = ApplicationContext::getContainer();
        $config = $container->get(ConfigInterface::class);

        $uri = $config->get('mongodb.uri', 'mongodb://localhost:27017');
        $this->client = new Client($uri);

        $this->db = $this->client->selectDatabase($config->get('mongodb.database_test', 'getpix_test'));

        $this->db->createCollection("users");
        $this->collection = $this->db->selectCollection("users");
    }

    protected function tearDown(): void
    {
        $this->db->dropCollection("users");

        parent::tearDown();
    }

    public function testSignUp()
    {
        $this->get('/api/ping')->assertOk()->assertSee('pong');
    }
}
