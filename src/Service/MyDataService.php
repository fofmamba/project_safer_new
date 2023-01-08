<?php

namespace App\Service;

use App\Repository\CategoryRepository;

class MyDataService
{
    private $myEntityRepository;

    public function __construct(CategoryRepository $myEntityRepository)
    {
        $this->myEntityRepository = $myEntityRepository;
    }

    public function getData()
    {
        return $this-> myEntityRepository->findAllData();
    }
}