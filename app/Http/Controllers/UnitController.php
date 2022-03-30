<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;
use DB;
class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units=Unit::all();
        return view('backend.admin.unit.index',compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin.unit.create');
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

        DB::table('units')->insert($data);
        return redirect()->route('unit.index')->with('success','Unit is Created successfully!');
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
    {   $data=Unit::findOrFail($id);
         return view('backend.admin.unit.edit',compact('data'));
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

        DB::table('units')->where('id',$id)->update($data);

         return redirect()->route('unit.index')->with('success',' Update successfully Done!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=Unit::findOrFail($id);
        $data->delete();
        return redirect()->route('unit.index')->with('success',' Delete successfully Done!');
    }
}
