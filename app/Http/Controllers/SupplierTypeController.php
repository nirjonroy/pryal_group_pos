<?php

namespace App\Http\Controllers;

use App\SupplierType;
use Illuminate\Http\Request;
use DB;
class SupplierTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $sups=SupplierType::all();
        return view('backend.admin.supplier-type.index',compact('sups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin.supplier-type.create');
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

        DB::table('supplier_types')->insert($data);
        return redirect()->route('supplier-type.index')->with('success','Type is Created successfully!');
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
         $data=SupplierType::findOrFail($id);
         return view('backend.admin.supplier-type.edit',compact('data'));
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

        DB::table('supplier_types')->where('id',$id)->update($data);

         return redirect()->route('supplier-type.index')->with('success',' Update successfully Done!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=SupplierType::findOrFail($id);
        $data->delete();
        return redirect()->route('supplier-type.index')->with('success',' Delete successfully Done!');
    }
}
