<?php

namespace App\Services\Mark;

use App\Models\Mark;

class StoreMarkService
{
    public function run(array $data): Mark
    {
        return Mark::create($data);
    }
}