<?php

namespace App\Services\ExpenseSplit;

use App\Models\ExpenseSplit;

class StoreExpenseSplitService
{
    public function run(array $data): ExpenseSplit
    {
        return ExpenseSplit::create($data);
    }
}
