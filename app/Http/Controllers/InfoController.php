<?php

namespace App\Http\Controllers;

use App\Info;
use Illuminate\Http\Request;
use DB;
class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {     if(!auth()->user()->can('info.view')){
            abort(403, 'Unauthorized action.');
        }
        $rows=Info::all();
         return view('info.index',compact('rows'));
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Info  $info
     * @return \Illuminate\Http\Response
     */
    public function show(Info $info)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Info  $info
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        if(!auth()->user()->can('info.edit')){
            abort(403, 'Unauthorized action.');
        }

        $row=Info::findOrFail($id);
         return view('info.edit',compact('row'));
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
       if(!auth()->user()->can('info.edit')){
            abort(403, 'Unauthorized action.');
        }
       $data=[
            'name'=>$request->name,
            'address'=>$request->address,
            'note'=>$request->note,
           

        ];

        DB::table('infos')->where('id',$id)->update($data);

         return redirect()->route('info.index')->with('success',' Update successfully Done!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Info  $info
     * @return \Illuminate\Http\Response
     */
    public function destroy(Info $info)
    {
        //
    }
}
