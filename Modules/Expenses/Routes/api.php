<?php


use Illuminate\Support\Facades\Route;
use Modules\Expenses\Http\Controllers\ExpenseController;

Route::apiResource("expenses", ExpenseController::class)->withoutMiddleware("auth:api");
