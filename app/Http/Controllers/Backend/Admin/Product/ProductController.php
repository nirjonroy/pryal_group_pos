<?php

namespace App\Http\Controllers\Backend\Admin\Product;

use App\Http\Controllers\Controller;
use App\Model\Backend\Admin\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Unit;
use App\QuantityStore;
use App\Store;
use DB;
class ProductController extends Controller
{
    
        

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('product.index')){
            abort(403, 'Unauthorized action.');
        }

        // dd(request()->alldata);
   
                
        $data['products'] = Product::whereNull('deleted_at')->latest()->get();
        return view('backend.admin.product.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('product.create')){
            abort(403, 'Unauthorized action.');
        }

        $units=Unit::all();
        return view('backend.admin.product.create',compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('product.create')){
            abort(403, 'Unauthorized action.');
        }

        $input = $request->except('_token');
        $validator = Validator::make($input,[
                'name' => 'required|min:2|max:255',
                'unit_id' => 'required|numeric',
                'description' => 'nullable',
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
        $product = new Product();
        $product->unit_id = $request->unit_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->unit_price = $request->unit_price;
         $product->sell_price = $request->sell_price;
        $product->created_by = Auth::user()->id;
        $save = $product->save();
        $product->sku = $product->id.mt_rand(1000, 9999);
        $product->save();
        // dd($product);
        if($save)
        {
            return redirect()->route('admin.product.index')->with('success','New product is created successfully!!');
        }
        else{
            return redirect()->back()->with('error','New Product is not created!!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!auth()->user()->can('product.index')){
            abort(403, 'Unauthorized action.');
        }

        $data['product'] = Product::findOrFail($id);
        return view('backend.admin.product.view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
    if(!auth()->user()->can('product.index')){
            abort(403, 'Unauthorized action.');
        }
          $data['units']=Unit::all();
        $data['product'] = Product::findOrFail($id);
        return view('backend.admin.product.edit',$data);
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
        if(!auth()->user()->can('product.index')){
            abort(403, 'Unauthorized action.');
        }

        $input = $request->except('_token');
        $validator = Validator::make($input,[
                'name' => 'required|min:2|max:255',
                'description' => 'nullable',
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
        $product = Product::findOrFail($id);
        $product->unit_id = $request->unit_id;
        $product->name = $request->name;
        $product->sell_price = $request->sell_price;
        $product->unit_price = $request->unit_price;
        $product->description = $request->description;
        $product->created_by = Auth::user()->id;
        $save = $product->save();
        if($save)
        {
            return redirect()->route('admin.product.index')->with('success',' Product is Updated successfully!!');
        }
        else{
            return redirect()->back()->with('error',' Product is not Updated!!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('product.index')){
            abort(403, 'Unauthorized action.');
        }

        $data=Product::findOrFail($id);
        $data->delete();
        return redirect()->route('admin.product.index')->with('success',' Delete successfully Done!');
    }
    
    public function product_stock(){
        
        $store_id=request('store_id');
        $query = DB::table('products as p')
                ->join('units', 'units.id', 'p.unit_id')
                ->join('quantity_stores as qs','qs.product_id','=', 'p.id')
                
                ->where('qs.quantity_available', '>', 0)
                ->select('p.id','p.name', 'units.name as uname', 'p.unit_price',
                DB::raw('ifnull(SUM(qs.quantity_available),0) as qty'),
                DB::raw('ifnull(SUM(qs.quantity_available * p.unit_price),0) as total_purchase_price'),
                DB::raw('ifnull(SUM(qs.quantity_available * p.sell_price),0) as total_sell_price'));
                
                
                if($store_id !=''){
                    $query->where('qs.store_id', $store_id);
                }
        $stock=$query->groupBy('p.id','p.name', 'p.unit_price', 'uname')
                    ->orderBy('name', 'asc')
                ->get();
                
        $productByStock = Store::all();   
                             
       // dd($stock);       
        return view('backend.admin.product.stock', compact('stock', 'productByStock'));
        
        
    }
}
