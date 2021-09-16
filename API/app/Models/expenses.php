<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class expenses extends Model
{
    use HasFactory;

    public function getExpenses(){
        $sql = DB::table('expenses')
        ->join('budgets', 'budgets.id', '=', "expenses.budget_id")
        ->select('expenses.*', "budgets.name as budgetName")
        ->where('expenses.user_id', auth()->user()->id)
        ->orderBy('expenses.created_at','desc')
        ->get();
        
        return $sql;
    }

    public function getExpensesByDate($date){
        $sql = DB::table('expenses')
                ->where('created_at', 'like', '%'. $date. '%')
                ->where('user_id', auth()->user()->id)
                ->get();
        return $sql;
    }

    public function getExpensesByBudgetId($budgetId){
        $expenses = DB::table('expenses')
        ->join('budgets', 'budgets.id', '=', 'expenses.budget_id')
        ->where('budgets.id', '=', $budgetId)
        ->orderBy('budgets.created_at','desc')
        ->select('expenses.*')
        ->get();

        return $expenses;
    }
}
