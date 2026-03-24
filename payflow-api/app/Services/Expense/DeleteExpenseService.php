<?php

namespace App\Services\Expense;

use App\Models\Expense;

class DeleteExpenseService
{
    public function run(Expense $expense): void
    {
        $expense->delete();
    }
}
