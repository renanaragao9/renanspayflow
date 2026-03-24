<?php

namespace Tests\Feature\ExpenseSplit;

use App\Models\Contact;
use App\Models\CostCenter;
use App\Models\Expense;
use App\Models\ExpenseInstallment;
use App\Models\ExpenseSplit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExpenseSplitCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        return User::create([
            'name' => 'Split User',
            'email' => 'split-user@example.com',
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
            'name' => 'CC Z',
            'type' => 'Interno',
            'due_date' => now()->addDays(30),
            'user_id' => $user->id,
        ]);

        return Expense::create([
            'description' => 'Compra Y',
            'purchase_date' => now()->toDateString(),
            'total_amount' => 500,
            'installments' => 2,
            'cost_center_id' => $costCenter->id,
        ]);
    }

    private function createExpenseInstallment(Expense $expense): ExpenseInstallment
    {
        return ExpenseInstallment::create([
            'due_date' => now()->addDays(30),
            'amount' => 250,
            'installment_number' => 1,
            'paid' => false,
            'expense_id' => $expense->id,
        ]);
    }

    public function test_can_create_expense_split(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);
        $installment = $this->createExpenseInstallment($expense);

        $contact = Contact::create(['name' => 'Contado Splits', 'email' => 'splits@contact.com', 'user_id' => $user->id]);

        $payload = [
            'amount' => 125.00,
            'paid' => false,
            'expense_installment_id' => $installment->id,
            'contact_id' => $contact->id,
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/expense-splits', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.amount', 125)
            ->assertJsonPath('data.paid', false);

        $this->assertDatabaseHas('expense_splits', ['amount' => 125.00, 'expense_installment_id' => $installment->id, 'contact_id' => $contact->id]);
    }

    public function test_can_list_expense_splits(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);
        $installment = $this->createExpenseInstallment($expense);
        $contact = Contact::create(['name' => 'Contado Splits 2', 'email' => 'splits2@contact.com', 'user_id' => $user->id]);

        ExpenseSplit::create(['amount' => 125.00, 'paid' => false, 'expense_installment_id' => $installment->id, 'contact_id' => $contact->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/expense-splits');

        $response->assertStatus(200)
            ->assertJsonFragment(['contact_id' => $contact->id]);
    }

    public function test_can_show_expense_split(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);
        $installment = $this->createExpenseInstallment($expense);
        $contact = Contact::create(['name' => 'Contado Splits 3', 'email' => 'splits3@contact.com', 'user_id' => $user->id]);

        $split = ExpenseSplit::create(['amount' => 125.00, 'paid' => false, 'expense_installment_id' => $installment->id, 'contact_id' => $contact->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/v1/expense-splits/{$split->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $split->id);
    }

    public function test_can_update_expense_split(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);
        $installment = $this->createExpenseInstallment($expense);
        $contact = Contact::create(['name' => 'Contado Splits 4', 'email' => 'splits4@contact.com', 'user_id' => $user->id]);

        $split = ExpenseSplit::create(['amount' => 125.00, 'paid' => false, 'expense_installment_id' => $installment->id, 'contact_id' => $contact->id]);

        $payload = ['paid' => true];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/v1/expense-splits/{$split->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.paid', true);

        $this->assertDatabaseHas('expense_splits', ['id' => $split->id, 'paid' => 1]);
    }

    public function test_can_delete_expense_split(): void
    {
        $user = $this->createUser();
        $expense = $this->createExpense($user);
        $installment = $this->createExpenseInstallment($expense);
        $contact = Contact::create(['name' => 'Contado Splits 5', 'email' => 'splits5@contact.com', 'user_id' => $user->id]);

        $split = ExpenseSplit::create(['amount' => 125.00, 'paid' => false, 'expense_installment_id' => $installment->id, 'contact_id' => $contact->id]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/v1/expense-splits/{$split->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('expense_splits', ['id' => $split->id]);
    }
}
