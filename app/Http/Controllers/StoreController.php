<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use DB;
class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items=Store::with('stocks')
                ->Leftjoin('quantity_stores as qs', 'qs.store_id', 'stores.id')
                ->Leftjoin('products as p', 'qs.product_id', 'p.id')
                ->select('stores.id','stores.name', 
                    DB::raw('SUM(qs.quantity_available) as qty'),   
                    DB::raw('SUM(qs.quantity_available * p.unit_price) as total_amount'))   
                ->groupBy('stores.id','stores.name')
                
            
            ->get();
        //dd($items);
        return view('store.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if(!auth()->user()->can('store.create')){
        //     abort(403, 'Unauthorized action.');
        // }
       return view('store.create');
    }

  /**
     * Store a newly created resource in storage.
  *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if(!auth()->user()->can('store.create')){
        //     abort(403, 'Unauthorized action.');
        // }
 

        $validatedData = $request->validate([
            'name'=> 'required|unique:stores'
         ]);
        Store::create($validatedData);
     return redirect()->route('stores.index')->with('success','Store is Create successfully!');
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
        // if(!auth()->user()->can('store.update')){
        //     abort(403, 'Unauthorized action.');
        // }
       $store=Store::find($id);
       //dd($store);
     return view('store.edit',compact('store'));
 }

 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

     
    //  if(!auth()->user()->can('store.update')){
    //         abort(403, 'Unauthorized action.');
    //     }
    $store=Store::find($id);

    $validatedData = $request->validate([
        'name' => 'required|unique:stores,name,'. $id,
    ]);

    $store->update($validatedData);


    return redirect()->route('stores.index')->with('success','Store is Update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
    
    public function store_product(){
        $store ='';
    }


}
