<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerType;
use DB;
class CustomerTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer=CustomerType::all();
        return view('backend.admin.customer-type.index',compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('backend.admin.customer-type.create');
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

        DB::table('customer_types')->insert($data);
        return redirect()->route('customer-type.index')->with('success','Type is Created successfully!');
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
    {
        $data=CustomerType::findOrFail($id);
         return view('backend.admin.customer-type.edit',compact('data'));
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

        DB::table('customer_types')->where('id',$id)->update($data);

         return redirect()->route('customer-type.index')->with('success',' Update successfully Done!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=CustomerType::findOrFail($id);
        $data->delete();
        return redirect()->route('customer-type.index')->with('success',' Delete successfully Done!');
    }
}
