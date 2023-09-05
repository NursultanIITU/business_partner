<?php

namespace App\UseCases;

use App\Repositories\IProductRepository;

class ProductCase extends BaseCase
{
    public function __construct(IProductRepository $IProductRepository)
    {
        $this->repository = $IProductRepository;
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
