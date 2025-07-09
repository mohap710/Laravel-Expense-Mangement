<?php

namespace Modules\Expenses\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Expenses\Http\Requests\StoreExpenseRequest;
use Modules\Expenses\Http\Requests\UpdateExpenseRequest;
use Modules\Expenses\Services\ExpenseService;

class ExpenseController extends Controller
{
    protected $expenseService;
    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Get all expenses
     *
     * This endpoint retrieves a list of expenses.
     * You can filter the expenses by category or a date range.
     *
     * @queryParam category string Filter expenses by a specific category. Must be one of: Groceries, Transportation, Health. Example: Health
     * @queryParam start_date date Filter expenses from this date (inclusive). Format: YYYY-MM-DD. Example: 2025-01-01
     * @queryParam end_date date Filter expenses up to this date (inclusive). Format: YYYY-MM-DD. Example: 2025-01-31
     *
     * @apiResourceCollection Modules\Expenses\Http\Resources\ExpenseResource
     * @apiResourceModel Modules\Expenses\Models\Expense
     */
    public function index(Request $request)
    {
        $result = $this->expenseService->getExpenses($request);

        return response()->json([
            'success' => $result['success'],
            'data' => $result['data'] ?? null,
            'message' => $result['message']
        ], $result['code']);
    }


    /**
     * Create a new expense
     *
     * This endpoint allows you to create a new expense record.
     *
     * @apiResource Modules\Expenses\Http\Resources\ExpenseResource
     * @apiResourceModel Modules\Expenses\Models\Expense
     */
    public function store(StoreExpenseRequest $request)
    {
        $result = $this->expenseService->createExpense($request->validated());

        return response()->json([
            'success' => $result['success'],
            'data' => $result['data'] ?? null,
            'message' => $result['message']
        ], $result['code']);
    }

    /**
     * Get a specific expense
     *
     * This endpoint retrieves the details of a single expense by its ID.
     *
     * @urlParam id string required The UUID of the expense. Example: 123e4567-e89b-12d3-a456-426614174000
     *
     * @response status=200 scenarios="Success" {"success": true, "data": {"id": "123e4567-e89b-12d3-a456-426614174000", "title": "Electricity Bill", "amount": 75.50, "category": "Utilities", "expense_date": "2025-06-20", "notes": "Monthly electricity payment", "created_at": "2025-06-15 08:00:00"}, "message": "Expense Loaded successfully."}
     * @response status=404 scenarios="Not Found" {"success": false, "data": null, "message": "Expense not found."}
     */
    public function show(string $id)
    {
        $result = $this->expenseService->getExpenseById($id);

        return response()->json([
            'success' => $result['success'],
            'data' => $result['data'] ?? null,
            'message' => $result['message']
        ], $result['code']);
    }


    /**
     * Update a new expense
     *
     * This endpoint allows you to update an existing expense record.
     *
     * @apiResource Modules\Expenses\Http\Resources\ExpenseResource
     * @apiResourceModel Modules\Expenses\Models\Expense
     */
    public function update(UpdateExpenseRequest $request, string $id)
    {
        $result = $this->expenseService->updateExpense($id, $request->validated());

        return response()->json([
            'success' => $result['success'],
            'data' => $result['data'] ?? null,
            'message' => $result['message']
        ], $result['code']);
    }

    /**
     * Delete an expense
     *
     * This endpoint deletes a specific expense record by its ID.
     *
     * @urlParam id string required The UUID of the expense to delete. Example: 123e4567-e89b-12d3-a456-426614174000
     *
     * @response status=204 scenarios="Expense Deleted" {"success" => true,"message" => "Expense deleted successfully","code" => 200}
     * @response status=404 scenarios="Not Found" {"success": false, "data": null, "message": "Expense not found."}
     */
    public function destroy(string $id)
    {
        $result = $this->expenseService->deleteExpense($id);

        return response()->json([
            'success' => $result['success'],
            'data' => $result['data'] ?? null,
            'message' => $result['message']
        ], $result['code']);
    }
}
