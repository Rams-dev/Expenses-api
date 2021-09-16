<?php

namespace App\Http\Controllers;

use App\Models\Budgets;
use App\Models\expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    private $Expenses;
    public function __construct()
    {
        $this->Expenses = new expenses;

        
    }

    public function index(Request $request)
    {
        //

        $budgets = Budgets::where('user_id', Auth()->user()->id)
                            ->get();
        return response()->json($budgets, 200);
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
     * name, amount, budget_start, budget_end
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $data = $request->input();
        $data['user_id'] = auth()->user()->id;;
        $data['created_at'] = now();
        $data['remaining'] = $data["amount"];
        $res = Budgets::insert($data);
        if($res){
            return response()->json(["message" => "Presupuesto agregado correctamente!"], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Budgets  $budgets
     * @return \Illuminate\Http\Response
     */
    public function show($budget_id)
    {
        //
        $budget = Budgets::findOrfail($budget_id);
        if(!$budget){
            return response()->json(["error"], 401);
        }
        $data["expenses"] = $this->Expenses->getExpensesByBudgetId($budget_id);
        $data["budgets"] = $budget;
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Budgets  $budgets
     * @return \Illuminate\Http\Response
     */
    public function edit(Budgets $budgets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Budgets  $budgets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Budgets $budgets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Budgets  $budgets
     * @return \Illuminate\Http\Response
     */
    public function destroy($budget_id)
    {
        //
        $budgetData = Budgets::findOrFail($budget_id);
        $expenses = expenses::where('budget_id',$budgetData->id)->get();
        if(count($expenses) > 0){
            foreach($expenses as $expense){
                expenses::destroy($expense->id);
            }
        }
        $budget = Budgets::destroy($budget_id);
        if($budget == 0){
            $message = "No existe dicho presupuesto";
        }else{
            $message = "Presupuesto eliminado con exito";
        }

        return response()->json(["message" => $message], 200);
        
    }
}
