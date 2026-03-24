<?php

namespace Tests\Feature\CostCenter;

use App\Models\CostCenter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CostCenterCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_cost_center(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);

        $payload = [
            'name' => 'Financeiro',
            'type' => 'Interno',
            'due_date' => now()->addDays(30)->toDateString(),
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/cost-centers', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.type', $payload['type']);

        $this->assertDatabaseHas('cost_centers', ['name' => 'Financeiro', 'type' => 'Interno']);
    }

    public function test_can_list_cost_centers(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);

        CostCenter::create(['user_id' => $user->id, 'name' => 'Contas a Pagar', 'type' => 'Interno', 'due_date' => now()->addDays(5)]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/cost-centers');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Contas a Pagar']);
    }

    public function test_can_show_cost_center(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user3@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);
        $costCenter = CostCenter::create(['user_id' => $user->id, 'name' => 'Contas A Receber', 'type' => 'Interno', 'due_date' => now()->addDays(10)]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/cost-centers/{$costCenter->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $costCenter->id);
    }

    public function test_can_update_cost_center(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user4@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);
        $costCenter = CostCenter::create(['user_id' => $user->id, 'name' => 'Contas iniciais', 'type' => 'Interno', 'due_date' => now()->addDays(10)]);

        $payload = ['name' => 'Contas Atualizado'];

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/cost-centers/{$costCenter->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $payload['name']);

        $this->assertDatabaseHas('cost_centers', ['id' => $costCenter->id, 'name' => 'Contas Atualizado']);
    }

    public function test_can_delete_cost_center(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user5@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Rua Teste',
        ]);
        $costCenter = CostCenter::create(['user_id' => $user->id, 'name' => 'Contas fmt', 'type' => 'Interno', 'due_date' => now()->addDays(10)]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/cost-centers/{$costCenter->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('cost_centers', ['id' => $costCenter->id]);
    }
}
