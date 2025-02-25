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
use Faker\{Factory, Generator};
use MongoDB\{Client, Database, Collection};

/**
 * @internal
 * @coversNothing
 */
class AuthTest extends TestCase
{

    private Generator $faker;
    private Client $client;
    private Database $db;
    private Collection $collection;

    private $user = [
        'firstName' => 'test',
        'lastName' => 'test',
        'email' => 'test@test.com',
        'password' => '123456'
    ];


    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
        $container = ApplicationContext::getContainer();
        $config = $container->get(ConfigInterface::class);

        $uri = $config->get('mongodb.uri', 'mongodb://localhost:27017');
        $this->client = new Client($uri);

        $this->db = $this->client->selectDatabase($config->get('mongodb.database_test', 'getpix_test'));

        $this->collection = $this->db->selectCollection("users");
    }

    protected function tearDown(): void
    {
        $this->collection->deleteMany([
            "email" => ['$ne' => $this->user['email']]
        ]);

        parent::tearDown();
    }

    public function testCorrectSignUp()
    {
        $user = !$this->collection->findOne(["email" => $this->user['email']]) ?
            $this->user :
            [
                'firstName' => $this->faker->firstName(),
                'lastName' => $this->faker->lastName(),
                'email' => $this->faker->email(),
                'password' => $this->faker->password(),
            ];

        $this->post('/api/sign-up', $user)->assertStatus(201);
    }

    public function testIncorrectSignUp()
    {
        $this->post('/api/sign-up', [
            'firstName' => 'test',
            'lastName' => null, # lastName.required
            'email' => $this->user['email'], # Email already exists
            'password' => '123' # password.mim
        ])->assertStatus(422);
    }

    public function testCorrectSignIn()
    {
        $this->post('/api/sign-in', [
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ])->assertJson(['status' => true]);
    }

    public function testIncorrectSignInByUserNotFound()
    {
        $this->post('/api/sign-in', [
            'email' => $this->user['email'] . ".br", # User not found
            'password' => $this->user['password']
        ])->assertStatus(401)->assertJson(['error' => 'user.not.found']);
    }

    public function testIncorrectSignInByIncorrectPassword()
    {
        $this->post('/api/sign-in', [
            'email' => $this->user['email'],
            'password' => '12345678' # Incorrect password
        ])->assertStatus(401)->assertJson(['error' => 'password.not.match']);
    }
}
