<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        return view('calculator.index');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'expression' => 'required|string',
        ]);

        try {
            $result = eval("return {$request->expression};");
            return response()->json(['result' => $result]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Invalid expression'], 400);
        }
    }
}
