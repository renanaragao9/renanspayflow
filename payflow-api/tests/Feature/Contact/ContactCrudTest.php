<?php

namespace Tests\Feature\Contact;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ContactCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_contact(): void
    {
        $user = User::create([
            'name' => 'Contact User',
            'email' => 'contact-user@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Endereco',
        ]);

        $payload = [
            'name' => 'Cliente x',
            'email' => 'cliente@example.com',
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/contacts', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Cliente x')
            ->assertJsonPath('data.email', 'cliente@example.com');

        $this->assertDatabaseHas('contacts', ['name' => 'Cliente x', 'email' => 'cliente@example.com']);
    }

    public function test_can_list_contacts(): void
    {
        $user = User::create([
            'name' => 'Contact User',
            'email' => 'contact-user-2@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Endereco',
        ]);

        Contact::create(['name' => 'Cliente Test', 'email' => 'test@cliente.com', 'user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/contacts');

        $response->assertStatus(200)->assertJsonFragment(['name' => 'Cliente Test']);
    }

    public function test_can_show_contact(): void
    {
        $user = User::create([
            'name' => 'Contact User',
            'email' => 'contact-user-3@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Endereco',
        ]);

        $contact = Contact::create(['name' => 'Mostra Contact', 'email' => 'show@contact.com', 'user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/v1/contacts/{$contact->id}");

        $response->assertStatus(200)->assertJsonPath('data.id', $contact->id);
    }

    public function test_can_update_contact(): void
    {
        $user = User::create([
            'name' => 'Contact User',
            'email' => 'contact-user-4@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Endereco',
        ]);

        $contact = Contact::create(['name' => 'Cliente Mod', 'email' => 'old@contact.com', 'user_id' => $user->id]);

        $payload = ['name' => 'Cliente Modificado'];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/v1/contacts/{$contact->id}", $payload);

        $response->assertStatus(200)->assertJsonPath('data.name', 'Cliente Modificado');
        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'name' => 'Cliente Modificado']);
    }

    public function test_can_delete_contact(): void
    {
        $user = User::create([
            'name' => 'Contact User',
            'email' => 'contact-user-5@example.com',
            'password' => Hash::make('password'),
            'birthdate' => now()->subYears(25),
            'phone' => '11111111',
            'gender' => 'M',
            'address' => 'Endereco',
        ]);

        $contact = Contact::create(['name' => 'Cliente Del', 'email' => 'delete@contact.com', 'user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/v1/contacts/{$contact->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }
}
