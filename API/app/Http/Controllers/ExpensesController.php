<?php

namespace App\Http\Controllers;

use App\Models\Budgets;
use App\Models\expenses;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $db;
    public function __construct()
    {
        
        $this->db = new expenses;
    } 

    public function index(Request $request)
    {

        // $expenses = expenses::all();
        $expenses = $this->db->getExpenses();
        return response($expenses); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * price, expense, budget_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $inputPrice = $request->input('price');

        $data = $request->input();
        $budget = Budgets::find($data['budget_id']);
        if($budget->remaining < $data["price"]){
            return response()->json(["error"=> "No puedes exceder el presupuesto"], 403);
        }
        $data['user_id'] = auth()->user()->id;
        $data['created_at'] = now();
        $res = expenses::insert($data);
        if($res){
            $budget->remaining = $budget->remaining - $data['price'];
            $budget->updated_at = now();
            $budget->save();
            return response()->json(["message"=> "Gasto Agregado"],200);
        }
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $expenses = expenses::findOrFail($id);      
        return response()->json($expenses, 200);
        

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function edit(expenses $expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, expenses $expenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $expenses = expenses::findOrFail($id);
        $budgetData = Budgets::findOrFail($expenses->budget_id);
        $budgetData->remaining += $expenses->price; 
        $budgetData->save();
        $expensesDelete = expenses::destroy($id);
        return response($expensesDelete);

    }

    public function getExpensesByDate(Request $request){
        $date = $request->input('date');
        $expenses = $this->db->getExpensesByDate($date);
        return response()->json($expenses, 200);
    }
}
