<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use function collect;

class OrderRepository implements OrderRepositoryInterface
{
    private $orders;
    private $items;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        //should i use object?? maybe not.
        $this->orders = collect(Http::get(env('ORDERS_ENDPOINT'))->json());
        $this->items = collect(Http::get(env('ITEMS_ENDPOINT'))->json());
        $this->userRepository = $userRepository;
    }

    public function find($id)
    {
        $order = $this->orders->firstWhere('id', $id);
        $order['items'] = $this->items->where('orderId', $id)->toArray();
        $order['user'] = $this->userRepository->find($order['customerId']);
        return $order;
    }

    public function getItem($id)
    {
        return $this->items->firstWhere('id', $id);
    }

    public function loadByDate(Carbon $date)
    {
        return $this->orders->filter(function ($order) use ($date) {
            return Carbon::parse($order['createdAt'])->isSameDay($date);
        })->map(function ($order) {
            $order['items'] = $this->items->where('orderId', $order['id'])->toArray();
            $order['user'] = $this->userRepository->find($order['customerId']);
            return $order;
        });
    }
}
