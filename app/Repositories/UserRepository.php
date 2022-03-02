<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Http;
use function collect;

class UserRepository implements UserRepositoryInterface
{
    private $users;

    public function __construct()
    {
        $this->users = collect(Http::get(env('USERS_ENDPOINT'))->json());
    }

    public function find($id)
    {
        return $this->users->firstWhere('id', $id);
    }
}
