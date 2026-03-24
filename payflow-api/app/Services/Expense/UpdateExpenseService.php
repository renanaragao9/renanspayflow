<?php

namespace App\Services\Expense;

use App\Models\Expense;

class UpdateExpenseService
{
    public function run(Expense $expense, array $data): Expense
    {
        $expense->update($data);

        return $expense;
    }
}
