<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_installments', function (Blueprint $table) {
            $table->id();
            $table->date('due_date');
            $table->decimal('amount', 10, 2);
            $table->integer('installment_number');
            $table->boolean('paid')->default(false);
            $table->foreignId('expense_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_installments');
    }
};
