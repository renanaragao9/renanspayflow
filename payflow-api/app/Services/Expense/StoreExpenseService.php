<?php

namespace App\Services\Expense;

use App\Models\Expense;

class StoreExpenseService
{
    public function run(array $data): Expense
    {
        return Expense::create($data);
    }
}
