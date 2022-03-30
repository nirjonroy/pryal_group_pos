<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('permission.view')){
            abort(403, 'Unauthorized action.');
        }
       $rows=Permission::orderBy('name','asc')->get();
       return view('permission.index',compact('rows'));
 }

 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('permission.view')){
            abort(403, 'Unauthorized action.');
        }
       return view('permission.create');
    }

  /**
     * Store a newly created resource in storage.
  *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('permission.view')){
            abort(403, 'Unauthorized action.');
        }
       $request->validate([
             'name'=> 'required|unique:permissions'
     ]);
        Permission::create(['name' => request()->name]);
     return redirect()->route('permission.index')->with('success','Permission is Craete successfully!');
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
        if(!auth()->user()->can('permission.view')){
            abort(403, 'Unauthorized action.');
        }
       $permissions=Permission::find($id);
     return view('permission.edit',compact('permissions'));
 }

 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
     
     if(!auth()->user()->can('permission.view')){
            abort(403, 'Unauthorized action.');
        }
    $permission=Permission::find($id);
    $permission->name=request()->name;
    $permission->save();

    return redirect()->route('permission.index')->with('success','Permission is Update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
