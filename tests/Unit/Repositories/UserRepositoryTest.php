<?php

namespace Tests\Unit\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use Tests\Helpers\Helpers;
use Tests\TestCase;
use function app;

class UserRepositoryTest extends TestCase
{
    use Helpers;

    /**
     * @var UserRepositoryInterface
     */
    private $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->fakeHttpRequests();

        $this->repo = app(UserRepositoryInterface::class);
    }

    public function test_it_can_find_an_order_given_the_id()
    {
        $user = $this->repo->find("a930b084-04ac-3ee1-bc32-877053c69481");
        $this->assertSame('Norris', $user['firstName']);
        $this->assertSame('Oduro', $user['lastName']);
        $this->assertSame('norrisjibril@gmail.com', $user['email']);
        $this->assertCount(2, $user['addresses']);
    }
}
