<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a verified user
        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    #[Test]
    public function authenticated_user_can_view_expenses_index()
    {
        $response = $this->actingAs($this->user)
            ->get('/expenses');
        
        $response->assertStatus(200);
        $response->assertViewIs('expenses.index');
    }

    #[Test]
    public function unauthenticated_user_cannot_view_expenses()
    {
        $response = $this->get('/expenses');
        
        $response->assertRedirect('/login');
    }

    #[Test]
    public function user_can_create_expense()
    {
        $expenseData = [
            'amount' => 25.50,
            'category' => 'Food',
            'date' => '2024-03-15',
            'description' => 'Lunch'
        ];
        
        $response = $this->actingAs($this->user)
            ->post('/expenses', $expenseData);
        
        $response->assertRedirect('/expenses');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('expenses', [
            'user_id' => $this->user->id,
            'amount' => 25.50,
            'category' => 'Food'
        ]);
    }

    #[Test]
    public function user_can_update_their_expense()
    {
        $expense = Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 10.00,
            'category' => 'Transport'
        ]);
        
        $updatedData = [
            'amount' => 15.00,
            'category' => 'Food',
            'date' => '2024-03-16',
            'description' => 'Updated expense'
        ];
        
        $response = $this->actingAs($this->user)
            ->put("/expenses/{$expense->id}", $updatedData);
        
        $response->assertRedirect('/expenses');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'amount' => 15.00,
            'category' => 'Food'
        ]);
    }

    #[Test]
    public function user_can_delete_their_expense()
    {
        $expense = Expense::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        $response = $this->actingAs($this->user)
            ->delete("/expenses/{$expense->id}");
        
        $response->assertRedirect('/expenses');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id
        ]);
    }

    #[Test]
    public function user_cannot_update_other_users_expense()
    {
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $otherUser->id
        ]);
        
        $response = $this->actingAs($this->user)
            ->put("/expenses/{$expense->id}", [
                'amount' => 100.00,
                'category' => 'Hacked',
                'date' => '2024-03-15'
            ]);
        
        $response->assertStatus(403); // Forbidden
    }

    #[Test]
    public function user_can_export_expenses_to_csv()
    {
        // Create some expenses
        Expense::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);
        
        $response = $this->actingAs($this->user)
            ->get('/expenses/export/csv');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type');
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $response->assertHeader('Content-Disposition');
    }

    #[Test]
    public function expense_requires_valid_amount()
    {
        $response = $this->actingAs($this->user)
            ->post('/expenses', [
                'amount' => 0, // Invalid
                'category' => 'Food',
                'date' => '2024-03-15'
            ]);
        
        $response->assertSessionHasErrors('amount');
    }

    #[Test]
    public function expense_requires_category()
    {
        $response = $this->actingAs($this->user)
            ->post('/expenses', [
                'amount' => 25.50,
                'category' => '', // Missing
                'date' => '2024-03-15'
            ]);
        
        $response->assertSessionHasErrors('category');
    }

    #[Test]
    public function expense_requires_date()
    {
        $response = $this->actingAs($this->user)
            ->post('/expenses', [
                'amount' => 25.50,
                'category' => 'Food',
                'date' => '' // Missing
            ]);
        
        $response->assertSessionHasErrors('date');
    }
}