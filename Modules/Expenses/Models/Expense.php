<?php

namespace Modules\Expenses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Expenses\Database\Factories\ExpenseFactory;

class Expense extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['title', 'amount', 'category', 'expense_date', 'notes'];


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return ExpenseFactory::new();
    }
}
