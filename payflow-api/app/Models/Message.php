<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends BaseModel
{
    protected $table = 'messages';

    protected $fillable = [
        'subject',
        'type',
        'channel',
        'message',
        'read_at',
        'user_id',
        'contact_id',
        'expense_installment_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function expenseInstallment(): BelongsTo
    {
        return $this->belongsTo(ExpenseInstallment::class);
    }
}
