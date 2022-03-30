<?php

namespace App\Http\Controllers;

use App\ProjectType;
use Illuminate\Http\Request;
use DB;
class ProjectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $projects=ProjectType::all();
        return view('backend.admin.project-type.index',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin.project-type.create');
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

        DB::table('project_types')->insert($data);
        return redirect()->route('project-type.index')->with('success','Type is Created successfully!');
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
    {   $data=ProjectType::findOrFail($id);
         return view('backend.admin.project-type.edit',compact('data'));
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

        DB::table('project_types')->where('id',$id)->update($data);

         return redirect()->route('project-type.index')->with('success',' Update successfully Done!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=ProjectType::findOrFail($id);
        $data->delete();
        return redirect()->route('project-type.index')->with('success',' Delete successfully Done!');
    }
}
