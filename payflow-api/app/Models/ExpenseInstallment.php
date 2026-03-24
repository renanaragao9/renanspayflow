<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseInstallment extends BaseModel
{
    protected $table = 'expense_installments';

    protected $fillable = [
        'due_date',
        'amount',
        'installment_number',
        'paid',
        'expense_id',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function splits()
    {
        return $this->hasMany(ExpenseSplit::class);
    }
}
