<?php

namespace Tests\Helpers;

use Illuminate\Support\Facades\Http;

trait Helpers
{
    public function fakeHttpRequests()
    {
        Http::fake([
            env('USERS_ENDPOINT') => Http::response([
                [
                    "id" => "a930b084-04ac-3ee1-bc32-877053c69481",
                    "firstName" => "Norris",
                    "lastName" => "Oduro",
                    "email" => "norrisjibril@gmail.com",
                    "addresses" => [
                        [
                            "type" => "billing",
                            "address" => "Neubertgasse 12b",
                            "city" => "Gera",
                            "zip" => "19247"
                        ],
                        [
                            "type" => "shipping",
                            "address" => "Grafweg 39",
                            "city" => "Kehl",
                            "zip" => "94954"
                        ]
                    ]
                ]
            ])
        ]);

        Http::fake([
            env('ORDERS_ENDPOINT') => Http::response([
                [
                    "id" => "6d7587cc-10b4-360f-8aef-1542b35305d8",
                    "customerId" => "a930b084-04ac-3ee1-bc32-877053c69481",
                    "createdAt" => "2019-07-07 08:46:46",
                    "notes" => "Test 1"
                ]
            ])
        ]);

        Http::fake([
            env('ITEMS_ENDPOINT') => Http::response([
                [
                    "orderId" => "6d7587cc-10b4-360f-8aef-1542b35305d8",
                    "id" => "c48cf79d-61b9-3314-92e2-c2a035df645b",
                    "name" => "Or Enscrmesa..abc Kn",
                    "quantity" => 1,
                    "basePrice" => -18.01
                ]
            ])
        ]);

    }
}
