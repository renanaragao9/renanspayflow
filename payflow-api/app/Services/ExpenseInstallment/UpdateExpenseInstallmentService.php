<?php

namespace App\Services\ExpenseInstallment;

use App\Models\ExpenseInstallment;

class UpdateExpenseInstallmentService
{
    public function run(ExpenseInstallment $expenseInstallment, array $data): ExpenseInstallment
    {
        $expenseInstallment->update($data);

        return $expenseInstallment;
    }
}
