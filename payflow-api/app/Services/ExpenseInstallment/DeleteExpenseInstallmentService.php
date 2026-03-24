<?php

namespace App\Services\ExpenseInstallment;

use App\Models\ExpenseInstallment;

class DeleteExpenseInstallmentService
{
    public function run(ExpenseInstallment $expenseInstallment): void
    {
        $expenseInstallment->delete();
    }
}
