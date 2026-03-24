<?php

namespace App\Services\ExpenseSplit;

use App\Models\ExpenseSplit;

class UpdateExpenseSplitService
{
    public function run(ExpenseSplit $expenseSplit, array $data): ExpenseSplit
    {
        $expenseSplit->update($data);

        return $expenseSplit;
    }
}
