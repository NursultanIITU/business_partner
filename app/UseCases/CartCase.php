<?php

namespace App\UseCases;

use App\Repositories\ICartRepository;

class CartCase extends BaseCase
{
    public function __construct(ICartRepository $ICartRepository)
    {
        $this->repository = $ICartRepository;
    }

    public function getCollection()
    {
        return $this->repository->list();
    }

    public function getListForApi()
    {
        return $this->repository->filterForApi();
    }

}
