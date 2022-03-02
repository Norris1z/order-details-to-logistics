<?php

namespace App\Services;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Services\ExportOrdersServiceInterface;
use App\Exceptions\NoOrdersAvailableException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class CSVOrdersExportService implements ExportOrdersServiceInterface
{
    private OrderRepositoryInterface $orderRepository;
    private Writer $writer;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->writer = Writer::createFromString();
    }

    private function setCsvHeaders()
    {
        $this->writer->insertOne([
            'orderID',
            'orderDate',
            'orderItemID',
            'orderItemName',
            'orderItemQuantity',
            'customerFirstName',
            'customerLastName',
            'customerAddress',
            'customerCity',
            'customerZipCode',
            'customerEmail'
        ]);
    }

    public function export(Carbon $date): string
    {
        $ordersForToday = $this->orderRepository->loadByDate($date);

        throw_if($ordersForToday->isEmpty(), new NoOrdersAvailableException("No orders found for {$date->toDateString()}"));

        $data = $ordersForToday->map(function ($order) {
            return collect($order['items'])->map(function ($orderItem) use ($order) {
                $address = collect($order['user']['addresses'])->firstWhere('type', 'shipping');
                if (is_null($address)) {
                    $address = collect($order['user']['addresses'])->firstWhere('type', 'billing');
                }
                return [
                    $order['id'],
                    $order['createdAt'],
                    $orderItem['id'],
                    $orderItem['name'],
                    $orderItem['quantity'],
                    $order['user']['firstName'],
                    $order['user']['lastName'],
                    $address['address'],
                    $address['city'],
                    $address['zip'],
                    $order['user']['email'],
                ];
            });
        })->flatten(1);

        $this->setCsvHeaders();
        $this->writer->insertAll($data->toArray());

        $path = "exports/{$date->toDateString()}.csv";

        Storage::disk('local')->put($path, $this->writer->toString());
        return storage_path($path);
    }
}
