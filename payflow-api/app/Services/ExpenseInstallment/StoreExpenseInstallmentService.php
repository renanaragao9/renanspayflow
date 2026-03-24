<?php

namespace App\Services\ExpenseInstallment;

use App\Models\ExpenseInstallment;

class StoreExpenseInstallmentService
{
    public function run(array $data): ExpenseInstallment
    {
        return ExpenseInstallment::create($data);
    }
}
