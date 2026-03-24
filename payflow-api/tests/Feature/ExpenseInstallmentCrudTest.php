<?php

namespace Tests\Feature;

use App\Models\CostCenter;
use App\Models\Expense;
use App\Models\ExpenseInstallment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExpenseInstallmentCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        return User::create([
            'name' => 'Installment User',
            'email' => 'installment-user@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(30),
            'phone' => '99999999',
            'gender' => 'M',
            'address' => 'Rua Exemplo',
        ]);
    }

    private function createExpense(User $user): Expense
    {
        $costCenter = CostCenter::create([
            'name' => 'CC Y',
            'type' => 'Interno',
            'due_date' => now()->addDays(30),
            'user_id' => $user->id,
        ]);

        return Expense::create([
            'description' => 'Compra XPTO',
            'purchase_date' => now()->toDateString(),
            'total_amount' => 500,
            'installments' => 3,
            'cost_center_id' => $costCenter->id,
        ]);
    }

    public function test_can_create_expense_installment(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);

        $payload = [
            'due_date' => now()->addDays(30)->toDateString(),
            'amount' => 166.67,
            'installment_number' => 1,
            'paid' => false,
            'expense_id' => $expense->id,
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/expense-installments', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.installment_number', 1)
            ->assertJsonPath('data.amount', 166.67);

        $this->assertDatabaseHas('expense_installments', ['expense_id' => $expense->id, 'installment_number' => 1]);
    }

    public function test_can_list_expense_installments(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);

        ExpenseInstallment::create([
            'due_date' => now()->addDays(30),
            'amount' => 166.67,
            'installment_number' => 1,
            'paid' => false,
            'expense_id' => $expense->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/expense-installments');

        $response->assertStatus(200)
            ->assertJsonFragment(['installment_number' => 1]);
    }

    public function test_can_show_expense_installment(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);

        $installment = ExpenseInstallment::create([
            'due_date' => now()->addDays(30),
            'amount' => 166.67,
            'installment_number' => 1,
            'paid' => false,
            'expense_id' => $expense->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/v1/expense-installments/{$installment->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $installment->id);
    }

    public function test_can_update_expense_installment(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);

        $installment = ExpenseInstallment::create([
            'due_date' => now()->addDays(30),
            'amount' => 166.67,
            'installment_number' => 1,
            'paid' => false,
            'expense_id' => $expense->id,
        ]);

        $payload = ['paid' => true];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/v1/expense-installments/{$installment->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.paid', true);

        $this->assertDatabaseHas('expense_installments', ['id' => $installment->id, 'paid' => 1]);
    }

    public function test_can_delete_expense_installment(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);

        $installment = ExpenseInstallment::create([
            'due_date' => now()->addDays(30),
            'amount' => 166.67,
            'installment_number' => 1,
            'paid' => false,
            'expense_id' => $expense->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/v1/expense-installments/{$installment->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('expense_installments', ['id' => $installment->id]);
    }
}
