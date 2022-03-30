<?php

namespace App\Http\Controllers;
use App\ExpenseCategory;
use Illuminate\Http\Request;
use DB;
class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $projects=ExpenseCategory::all();
        return view('backend.admin.expense-category.index',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin.expense-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $data=[
            'name'=>$request->name
        ];

        DB::table('expense_categories')->insert($data);
        return redirect()->route('expense-category.index')->with('success','Expense Category is Created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   $data=ExpenseCategory::findOrFail($id);
         return view('backend.admin.expense-category.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $data=[
            'name'=>$request->name

        ];

        DB::table('expense_categories')->where('id',$id)->update($data);

         return redirect()->route('expense-category.index')->with('success',' Update successfully Done!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=ExpenseCategory::findOrFail($id);
        $data->delete();
        return redirect()->route('expense-category.index')->with('success',' Delete successfully Done!');
    }
}
