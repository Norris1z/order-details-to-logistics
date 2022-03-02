<?php

namespace Tests\Unit\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use Carbon\Carbon;
use Tests\Helpers\Helpers;
use Tests\TestCase;
use function app;

class OrderRepositoryTest extends TestCase
{
    use Helpers;

    /**
     * @var OrderRepositoryInterface
     */
    private $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->fakeHttpRequests();

        $this->repo = app(OrderRepositoryInterface::class);
    }

    public function test_it_can_find_an_order_given_the_id()
    {
        $order = $this->repo->find("6d7587cc-10b4-360f-8aef-1542b35305d8");
        $this->assertSame('a930b084-04ac-3ee1-bc32-877053c69481', $order['customerId']);
        $this->assertSame('Test 1', $order['notes']);
    }

    public function test_it_can_load_an_item_by_id()
    {
        $order = $this->repo->getItem("c48cf79d-61b9-3314-92e2-c2a035df645b");
        $this->assertSame('6d7587cc-10b4-360f-8aef-1542b35305d8', $order['orderId']);
    }

    public function test_it_loads_an_order_with_the_items()
    {
        $order = $this->repo->find("6d7587cc-10b4-360f-8aef-1542b35305d8");
        $this->assertCount(1, $order['items']);
    }

    public function test_it_loads_an_order_with_the_user()
    {
        $order = $this->repo->find("6d7587cc-10b4-360f-8aef-1542b35305d8");
        $this->assertSame('Norris', $order['user']['firstName']);
    }

    public function test_it_can_load_an_order_by_date()
    {
        $orders = $this->repo->loadByDate(Carbon::parse("2019-07-07 08:46:46"));
        $this->assertCount(1, $orders);

        $orders = $this->repo->loadByDate(now());
        $this->assertCount(0, $orders);
    }
}
