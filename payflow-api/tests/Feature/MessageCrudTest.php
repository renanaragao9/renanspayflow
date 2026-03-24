<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\CostCenter;
use App\Models\Expense;
use App\Models\ExpenseInstallment;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MessageCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        return User::create([
            'name' => 'Message User',
            'email' => 'message-user@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(30),
            'phone' => '99999999',
            'gender' => 'M',
            'address' => 'Rua Exemplo',
        ]);
    }

    private function createExpenseData(User $user): array
    {
        $costCenter = CostCenter::create([
            'name' => 'CC Message',
            'type' => 'Interno',
            'due_date' => now()->addDays(30),
            'user_id' => $user->id,
        ]);

        $expense = Expense::create([
            'description' => 'Compra Message',
            'purchase_date' => now()->toDateString(),
            'total_amount' => 100,
            'installments' => 1,
            'cost_center_id' => $costCenter->id,
        ]);

        $installment = ExpenseInstallment::create([
            'due_date' => now()->addDays(30),
            'amount' => 100,
            'installment_number' => 1,
            'paid' => false,
            'expense_id' => $expense->id,
        ]);

        return ['expense' => $expense, 'installment' => $installment];
    }

    public function test_can_create_message(): void
    {
        $user = $this->createUser();
        $contact = Contact::create(['name' => 'Contato Msg', 'email' => 'contato@msg.com', 'user_id' => $user->id]);
        $data = $this->createExpenseData($user);

        $payload = [
            'subject' => 'Assunto test',
            'type' => 'manual',
            'channel' => 'system',
            'message' => 'Texto de teste',
            'read_at' => null,
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'expense_installment_id' => $data['installment']->id,
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/messages', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.subject', 'Assunto test')
            ->assertJsonPath('data.channel', 'system');

        $this->assertDatabaseHas('messages', ['subject' => 'Assunto test', 'contact_id' => $contact->id]);
    }

    public function test_can_list_messages(): void
    {
        $user = $this->createUser();
        $contact = Contact::create(['name' => 'Contato Msg 2', 'email' => 'contato2@msg.com', 'user_id' => $user->id]);
        $data = $this->createExpenseData($user);

        Message::create([
            'subject' => 'Lista',
            'type' => 'manual',
            'channel' => 'system',
            'message' => 'Teste lista',
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'expense_installment_id' => $data['installment']->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/messages');

        $response->assertStatus(200)
            ->assertJsonFragment(['subject' => 'Lista']);
    }

    public function test_can_show_message(): void
    {
        $user = $this->createUser();
        $contact = Contact::create(['name' => 'Contato Msg 3', 'email' => 'contato3@msg.com', 'user_id' => $user->id]);
        $data = $this->createExpenseData($user);

        $message = Message::create([
            'subject' => 'Mostra',
            'type' => 'manual',
            'channel' => 'system',
            'message' => 'Teste show',
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'expense_installment_id' => $data['installment']->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/v1/messages/{$message->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $message->id);
    }

    public function test_can_update_message(): void
    {
        $user = $this->createUser();
        $contact = Contact::create(['name' => 'Contato Msg 4', 'email' => 'contato4@msg.com', 'user_id' => $user->id]);
        $data = $this->createExpenseData($user);

        $message = Message::create([
            'subject' => 'Inicial',
            'type' => 'manual',
            'channel' => 'system',
            'message' => 'Texto inicial',
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'expense_installment_id' => $data['installment']->id,
        ]);

        $payload = ['message' => 'Texto atualizado', 'read_at' => now()->toDateTimeString()];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/v1/messages/{$message->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.message', 'Texto atualizado');

        $this->assertDatabaseHas('messages', ['id' => $message->id, 'message' => 'Texto atualizado']);
    }

    public function test_can_delete_message(): void
    {
        $user = $this->createUser();
        $contact = Contact::create(['name' => 'Contato Msg 5', 'email' => 'contato5@msg.com', 'user_id' => $user->id]);
        $data = $this->createExpenseData($user);

        $message = Message::create([
            'subject' => 'Excluir',
            'type' => 'manual',
            'channel' => 'system',
            'message' => 'Texto excluir',
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'expense_installment_id' => $data['installment']->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/v1/messages/{$message->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('messages', ['id' => $message->id]);
    }
}
