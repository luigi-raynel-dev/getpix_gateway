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

/**
 * @internal
 * @coversNothing
 */
class UserTest extends TestCase
{
    private $access_token;

    protected function setUp(): void
    {
        parent::setUp();

        $response = $this->post('/api/sign-in', [
            'email' => 'test@test.com',
            'password' => '123456'
        ]);

        $data = json_decode((string) $response->getBody(), true);

        if (array_key_exists('access_token', $data)) $this->access_token = $data['access_token'];
        else $this->markTestSkipped('No access token');
    }

    public function testCorrectGetMyUser()
    {
        $response = $this->get('/api/users/me', [], [
            'Authorization' => 'Bearer ' . $this->access_token,
        ]);

        $response->assertStatus(200);
    }

    public function testIncorrectGetMyUser()
    {
        $this->get('/api/users/me', [], [])->assertStatus(401);
    }
}
