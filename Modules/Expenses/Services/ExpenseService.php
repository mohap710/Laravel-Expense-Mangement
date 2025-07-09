<?php

namespace Modules\Expenses\Services;

use Illuminate\Support\Facades\Log;
use Modules\Expenses\Models\Expense;
use Modules\Expenses\Http\Resources\ExpenseResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ExpenseService
{
    public function getExpenses(Request $request)
    {
        try {
            $query = Expense::query();

            $this->applyFilters($query, $request);

            $expenses = $query->orderBy('expense_date', 'desc')->get();

            return [
                'success' => true,
                'message' => 'Expenses Loaded successfully',
                'data' => ExpenseResource::collection($expenses),
                'code' => 200
            ];
        } catch (\Exception $e) {
            Log::debug('Failed to fetch expenses: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch expenses',
                'code' => 500
            ];
        }
    }
    public function getExpenseById(string $id)
    {
        try {
            $expense = Expense::findOrFail($id);

            return [
                'success' => true,
                'message' => 'Expense Loaded successfully',
                'data' => new ExpenseResource($expense),
                'code' => 200
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'Expense not found',
                'code' => 404
            ];
        } catch (\Throwable $e) {
            Log::debug('Failed to get Expense by Id: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to get expense',
                'code' => 500
            ];
        }
    }
    public function createExpense($data)
    {
        try {
            $expense = Expense::create($data);
            return [
                'success' => true,
                'data' => new ExpenseResource($expense),
                'message' => 'Expense created successfully',
                'code' => 201
            ];
        } catch (\Throwable $th) {

            Log::debug('Expense creation failed: ' . $th->getMessage());
            return [
                'success' => false,
                'message' => 'Expense creation failed',
                'code' => 400
            ];
        }
    }
    public function updateExpense(string $id, $data)
    {
        try {
            $expense = Expense::findOrFail($id);

            $expense->update($data);

            return [
                'success' => true,
                'message' => 'Expense Updated successfully',
                'code' => 200
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'Expense not found',
                'code' => 404
            ];
        } catch (\Throwable $e) {
            Log::debug('Failed to update expense: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update expense',
                'code' => 500
            ];
        }
    }
    public function deleteExpense(string $id)
    {
        try {
            $expense = Expense::findOrFail($id);

            $expense->delete();

            return [
                'success' => true,
                'message' => 'Expense deleted successfully',
                'code' => 200
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'Expense not found',
                'code' => 404
            ];
        } catch (\Throwable $e) {
            Log::debug('Failed to delete expense: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to delete expense',
                'code' => 500
            ];
        }
    }

    protected function applyFilters($query, Request $request)
    {
        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->input('category'));
        }

        // Date range filters
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('expense_date', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('expense_date', '<=', $request->input('end_date'));
        }
    }
}
