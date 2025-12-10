<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ExpenseService;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use App\Models\Expense;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

class ExpenseServiceTest extends TestCase
{
    protected $expenseService;
    protected $expenseRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->expenseRepositoryMock = Mockery::mock(ExpenseRepositoryInterface::class);
        $this->expenseService = new ExpenseService($this->expenseRepositoryMock);
    }

    #[Test]
    public function it_can_create_expense_with_valid_data()
    {
        $data = [
            'user_id' => 1,
            'amount' => 50.00,
            'category' => 'Food',
            'date' => '2024-03-15'
        ];
        
        $this->expenseRepositoryMock
            ->shouldReceive('createExpense')
            ->with($data)
            ->once()
            ->andReturn(new Expense($data));
        
        $result = $this->expenseService->createExpense($data);
        
        $this->assertInstanceOf(Expense::class, $result);
        $this->assertEquals(50.00, $result->amount);
    }

    #[Test]
    public function it_throws_exception_when_creating_expense_with_zero_amount()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $data = [
            'user_id' => 1,
            'amount' => 0,
            'category' => 'Food',
            'date' => '2024-03-15'
        ];
        
        $this->expenseService->createExpense($data);
    }

    #[Test]
    public function it_throws_exception_when_creating_expense_with_negative_amount()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $data = [
            'user_id' => 1,
            'amount' => -10,
            'category' => 'Food',
            'date' => '2024-03-15'
        ];
        
        $this->expenseService->createExpense($data);
    }

    #[Test]
    public function it_can_get_monthly_total()
    {
        $userId = 1;
        $month = '03';
        
        $this->expenseRepositoryMock
            ->shouldReceive('getMonthlyTotal')
            ->with($userId, $month)
            ->once()
            ->andReturn(150.75);
        
        $result = $this->expenseService->getMonthlyTotal($userId, $month);
        
        $this->assertEquals(150.75, $result);
    }

    #[Test]
    public function it_can_export_expenses_to_csv()
    {
        $userId = 1;
        $expenses = collect([
            new Expense([
                'user_id' => $userId,
                'amount' => 25.50,
                'category' => 'Food',
                'date' => '2024-03-15',
                'description' => 'Lunch'
            ]),
            new Expense([
                'user_id' => $userId,
                'amount' => 12.00,
                'category' => 'Transport',
                'date' => '2024-03-16',
                'description' => 'Bus'
            ]),
        ]);
        
        $this->expenseRepositoryMock
            ->shouldReceive('getUserExpenses')
            ->with($userId, [])
            ->once()
            ->andReturn($expenses);
        
        $result = $this->expenseService->exportToCsv($userId);
        
        $this->assertStringContainsString('Date,Category,Amount,Description', $result);
        $this->assertStringContainsString('2024-03-15,Food,25.50,Lunch', $result);
        $this->assertStringContainsString('2024-03-16,Transport,12.00,Bus', $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}