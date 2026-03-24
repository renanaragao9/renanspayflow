<?php

namespace App\Services\ExpenseSplit;

use App\Models\ExpenseSplit;

class DeleteExpenseSplitService
{
    public function run(ExpenseSplit $expenseSplit): void
    {
        $expenseSplit->delete();
    }
}
