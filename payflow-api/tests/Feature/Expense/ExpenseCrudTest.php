<?php

namespace Tests\Feature\Expense;

use App\Models\CostCenter;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExpenseCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_expense(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user-expense1@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);

        $costCenter = CostCenter::create([
            'name' => 'Finance',
            'type' => 'Interno',
            'due_date' => now()->addDays(30),
            'user_id' => $user->id,
        ]);

        $payload = [
            'description' => 'Compra escritório',
            'purchase_date' => now()->toDateString(),
            'total_amount' => 123.45,
            'installments' => 2,
            'cost_center_id' => $costCenter->id,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/expenses', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.description', $payload['description'])
            ->assertJsonPath('data.total_amount', 123.45);

        $this->assertDatabaseHas('expenses', ['description' => 'Compra escritório', 'cost_center_id' => $costCenter->id]);
    }

    public function test_can_list_expenses(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user-expense2@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);
        $costCenter = CostCenter::create(['name' => 'Finance', 'type' => 'Interno', 'due_date' => now()->addDays(30), 'user_id' => $user->id]);
        Expense::create(['description' => 'Energia', 'purchase_date' => now()->toDateString(), 'total_amount' => 200, 'installments' => 1, 'cost_center_id' => $costCenter->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/expenses');

        $response->assertStatus(200)
            ->assertJsonFragment(['description' => 'Energia']);
    }

    public function test_can_show_expense(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user-expense3@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);
        $costCenter = CostCenter::create(['name' => 'Finance', 'type' => 'Interno', 'due_date' => now()->addDays(30), 'user_id' => $user->id]);
        $expense = Expense::create(['description' => 'Água', 'purchase_date' => now()->toDateString(), 'total_amount' => 75.5, 'installments' => 1, 'cost_center_id' => $costCenter->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/v1/expenses/{$expense->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $expense->id);
    }

    public function test_can_update_expense(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user-expense4@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);
        $costCenter = CostCenter::create(['name' => 'Finance', 'type' => 'Interno', 'due_date' => now()->addDays(30), 'user_id' => $user->id]);
        $expense = Expense::create(['description' => 'Internet', 'purchase_date' => now()->toDateString(), 'total_amount' => 120, 'installments' => 1, 'cost_center_id' => $costCenter->id]);

        $payload = ['description' => 'Internet Atualizada', 'total_amount' => 130.99];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/v1/expenses/{$expense->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.description', 'Internet Atualizada')
            ->assertJsonPath('data.total_amount', 130.99);

        $this->assertDatabaseHas('expenses', ['id' => $expense->id, 'description' => 'Internet Atualizada']);
    }

    public function test_can_delete_expense(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user-expense5@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);
        $costCenter = CostCenter::create(['name' => 'Finance', 'type' => 'Interno', 'due_date' => now()->addDays(30), 'user_id' => $user->id]);
        $expense = Expense::create(['description' => 'Telefone', 'purchase_date' => now()->toDateString(), 'total_amount' => 90, 'installments' => 1, 'cost_center_id' => $costCenter->id]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/v1/expenses/{$expense->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('expenses', ['id' => $expense->id]);
    }
}
