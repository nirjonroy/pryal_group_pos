<?php

namespace App\Http\Controllers;

use App\Model\Backend\Admin\Payment\Payment_method;
use App\Model\Backend\Admin\Product\Product;
use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Company\Company;
use Illuminate\Support\Facades\DB;
use App\Utils\QuantityManage;
use Illuminate\Http\Request;
use App\Sell_detail;
use App\Customer;
use App\Store;
use App\Sell;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class StockReturnController extends Controller
{
    protected $quantityManage;
    public function __construct(QuantityManage $quantity_manage)
    {
        $this->quantityManage=$quantity_manage;
        
    }


    public function index(){
        
        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();
        $pro =Project::orderby('name','asc');
                if(request()->status !=''){
                    $pro->where('working_status', request()->status);
                }

                if(request()->company_id!=''){
                    $pro->where('company_id',request()->company_id);
                }
        $data['projects']=$pro->get();
        
        // $data['sups'] =Supplier::orderBy('name','asc')->get();
        $com =Company::orderby('name','asc');
                if(request()->type_id !=''){
                    $com->where('type_id',request()->type_id);
                }
        $data['coms']=$com->get();
        
        $customer = Customer::orderby('name', 'asc');
                if(request()->customer_id !=''){
                    $com->where('customer_id',request()->customer_id);
                }
        $data['customers']=$customer->get();  
        
        $store = Store::orderby('name', 'asc');
                if(request()->store_id !=''){
                    $com->where('store_id',request()->store_id);
                }
        $data['stores']=$store->get();
        

        $query=Sell::with('projects','projects.companies')->where('type','stock_return');
        
        if(request()->date_start and request()->date_end !=''){
            $query->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
        }

        if(request()->project_id !=''){
            $query->where('project_id',request()->project_id);
        }

        if(request()->company_id!=''){
            $query->where('company_id',request()->company_id);
        }

        if(request()->supplier_id !=''){
            $query->where('supplier_id',request()->supplier_id );
        }
        
        if(request()->customer_id !=''){
            $query->where('customer_id',request()->customer_id );
        }
        
        if(request()->store_id !=''){
            $query->where('store_id',request()->store_id );
        }

        

        if(request()->alldata){
           $data['sells']=$query->latest()->paginate(2000);
          }elseif(request()->date_start and request()->date_end !=''){
              $data['sells']=$query->latest()->paginate(2000);
          }else{
           $data['sells']=$query->latest()->paginate(30);

          }
       


       $query_2=Sell::where('type','stock_return');
                 if(request()->date_start !=''){
                        $query_2->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                    }
                    
                    if(request()->project_id !=''){
                        $query_2->where('project_id',request()->project_id);
                    }
            
                    if(request()->company_id!=''){
                        $query_2->where('company_id',request()->company_id);
                    }
            
                    if(request()->supplier_id !=''){
                        $query_2->where('supplier_id',request()->supplier_id );
                    }
                    
                    if(request()->customer_id !=''){
                        $query_2->where('customer_id',request()->customer_id );
                    }
                    
                    if(request()->store_id !=''){
                        $query_2->where('store_id',request()->store_id );
                    }
        $data['total_summery']=$query_2->sum('total_price');
                
        // dd($data);
        return view('backend.admin.stock_return.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){

        if (request()->ajax()) {
            
            $returnCart = session()->has('returnCart') ? session()->put('returnCart',[])  :[];
            $view = view('backend.admin.stock_return.stock_return_cart')->render();
            if($returnCart)
            {
                return response()->json([
                    'status' => true,
                    'data' => $view
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => $view
                ]);
            }  
        }
        
        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();                      
        $data['stores'] =Store::all();   
        $data['customers'] =Customer::all();   
        
        
        return view('backend.admin.stock_return.create', $data);
    }


    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        $cash_hand = (purchaseInHand($request->supplier_id) + $request->totalProductPrice);

        DB::beginTransaction();
        try
        {
            $input = $request->except('_token');
            $validator = Validator::make($input,[
                    
                    'store_id' => 'required',
                    'customer_id' => 'required',
                    'product_id.*' => 'required',
                    'totalProductPrice' => 'required',
            ],
            [
                'totalProductPrice.required' => 'Please Select Product Field & others',
            ]
        );
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

                $invoice_no = mt_rand(10000000, 99999999);
                $sell  = new Sell();
                $sell->invoice_no = $invoice_no;
                $sell->type = 'stock_return';
                $sell->store_id = $request->store_id;
                $sell->customer_id = $request->customer_id;
                $sell->project_id = $request->project_id;
                $sell->company_id = $request->company_id;
                $sell->description = $request->description;
                $sell->total_quantity = $request->totalProductQuantity;
                $sell->total_price = $request->totalProductPrice;
                $sell->cash_hand = $cash_hand;
                $sell->user_id = Auth::user()->id;
                $sell->created_at = $_POST['date'].' '.date('h:i:s');
                $save =  $sell->save();
                $sells_id = $sell->id;
                $store_id =$sell->store_id;
                $project_id =$sell->project_id;
                if($request->product_id)
                { 
                    foreach($request->product_id as $key=> $product)
                    {
                        
                        $product_id=$request->product_id[$key];
                        $quantity=$request->quantity[$key];

                        $sell_detail  = new Sell_detail();
                        $sell_detail->invoice_no = $invoice_no;
                        $sell_detail->sells_id = $sells_id;
                        $sell_detail->product_id = $product_id;
                        $sell_detail->quantity = $quantity;
                        $sell_detail->unit_price = $request->sale_unit_price[$key];
                        $sell_detail->total_price = $request->quantity[$key] * $request->sale_unit_price[$key];
                        $sell_detail->description = $request->description;
                        $sell_detail->created_by = Auth::user()->id;
                        $sell_detail->created_at = $_POST['date'].' '.date('h:i:s');
                        $sell_detail->save();

                        $this->quantityManage->updateQuantityNew($product_id,$store_id,$quantity);
                        $this->quantityManage->returnQuantity($product_id,$project_id,$quantity);
                    }
                }


                    

                if($request->payment_amount &&  $request->payment_amount > 0)
                {
                    $payment  = new Sell();
                    $payment->invoice_no = $invoice_no;
                    $payment->type = 'return_payment';
                    $payment->total_price = $request->payment_amount;
                    $payment->method_id = $request->payment_method_id;
                    
                    $payment->user_id = Auth::user()->id;
                    $payment->cash_hand = $cash_hand - $request->payment_amount;
                    $payment->save();
                }
                DB::commit();
                if($save)
                {
                    session(['returnCart' => []]);
                    return redirect()->route('stock_returns.index')->with('success','New Stcok Return is Created successfully!');
                }
                else{
                    return redirect()->back()->with('error','Stcok Return is Not Created!');
                }
        }
        catch (\Throwable $e)
        {
            DB::rollback();
            throw $e;
            return redirect()->back()->with('error','Somethings went to wrong!');
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
       
        $data['sell'] = Sell::with('sellDetails', 'customer')->findOrFail($id);
        return view('backend.admin.stock_return.show',$data);
        
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sell=Sell::with('sellDetails')->findOrFail($id);
        // dd($sell);
        $project_company_id=$sell->company_id;
        
        $product_id=$sell->sellDetails->pluck('product_id')->toArray();
    
        

        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();
        $data['projects'] = Project::where('company_id',$project_company_id)->whereNull('deleted_at')->get();
        $data['sell'] = $sell;
        $data['stores'] =Store::all(); 
        
        $data['product'] =  Product::join('sells_details','sells_details.product_id', 'products.id')
                        ->join('sells','sells_details.sells_id', 'sells.id')
                        ->where('sells.project_id',$sell->project_id)
                        ->where('sells.type','sell')
                        ->whereNotIn('products.id', $product_id)
             
                        ->select('products.id', 'products.name',
                            DB::raw('SUM(sells_details.quantity - sells_details.return_quantity) as qty'))
                        ->groupby('products.id', 'products.name')
                        ->having('qty', '>',0)
                        ->orderBy('name','asc')->get();
                        
        return view('backend.admin.stock_return.edit', $data);
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

        DB::beginTransaction();
        try
        {
            $input = $request->except('_token');
            $validator = Validator::make($input,[
                    'store_id' => 'required',
                    'product_id.*' => 'required',
                    'totalProductPrice' => 'required',
            ],
            
            [
                'totalProductPrice.required' => 'Please Select Product Field & others',
            ]
        );
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

                

                $sell  = Sell::find($id);
                $store_id=$sell->store_id;
                $total_price = $sell->total_price;



                    $sell->project_id = $request->project_id;
      
                    $sell->description = $request->description;
                    $sell->total_quantity = $request->totalProductQuantity;
                    $sell->total_price = $request->totalProductPrice;
                    $sell->user_id = Auth::user()->id;
                    $save =  $sell->save();

                    
                    
                    if(isset($request->product_id))
                    {
                        $edit_lines=[];
                        foreach($request->product_id as $key=> $product)
                        {
                     
                            $product_id=$request->product_id[$key];
                            $new_quantity=$request->quantity[$key];

                            if (isset($request->details_id[$key])) {
                                $sell_detail=Sell_detail::find($request->details_id[$key]);

                                $this->quantityManage->updateQuantityNew($product_id,$store_id,$new_quantity,$sell_detail->quantity);
                                $this->quantityManage->returnQuantity($product_id,$sell->project_id,$new_quantity,$sell_detail->quantity);

                                $sell_detail->unit_price = $request->sale_unit_price[$key];
                                $sell_detail->quantity = $new_quantity;
                                

                                
                            }else{

                               $sell_detail  = new Sell_detail();
                                
                                $sell_detail->sells_id = $id;
                                $sell_detail->product_id = $request->product_id[$key];
                                $sell_detail->quantity = $new_quantity;
                                $sell_detail->unit_price = $request->sale_unit_price[$key];
                                $sell_detail->total_price = $request->quantity[$key] * $request->sale_unit_price[$key];
                                $sell_detail->description = $request->description;

                                $sell_detail->created_by = Auth::user()->id;

                                $this->quantityManage->updateQuantityNew($product_id,$store_id,$new_quantity);
                                $this->quantityManage->returnQuantity($product_id,$sell->project_id,$new_quantity);
                            }
                            $sell_detail->save();
                            $edit_lines[]=$sell_detail->id;

                        }


                    }
                    

                     
     

                
                


                $delete_data=$sell->sellDetails()->whereNOtIn('id',$edit_lines)->get()->pluck('id')->toArray();

                if (!empty($delete_data)) {

                    $items=Sell_detail::whereIn('id',$delete_data)->get();

                    foreach ($items as $key => $v) {
                       $this->quantityManage->decreaseQuantity($v->product_id,$sell->store_id,$v->quantity);
                    }
                    Sell_detail::whereIn('id',$delete_data)->delete();
                }
               
                DB::commit();
                if($save)
                {
                    session(['returnCart' => []]);
                    return redirect()->back()->with('success','Purchese is Update successfully!');
                }
                else{
                    return redirect()->back()->with('error','Purchese is Not Update!');
                }
        }
        catch (\Throwable $e)
        {
            // DB::rollback();
            throw $e;
            return redirect()->back()->with('error','Somethings weng to wrong!');
        }
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        DB::beginTransaction();
    try
        {
        $sell=Sell::find($id); 
        
        foreach ($sell->sellDetails as $v) {
            $this->quantityManage->decreaseQuantity($v->product_id,$sell->store_id,$v->quantity);
            $this->quantityManage->returnUpdateQuantity($v->product_id,$sell->project_id,$v->quantity);
        }
        $sell->sellDetails()->delete();
        $sell->delete();

        DB::commit();
        return redirect()->back()->with('success','Sell Return  Deleted successfully!');
    }   catch (\Throwable $e)
        {
            // DB::rollback();
            throw $e;
            return redirect()->back()->with('error','Somethings weng to wrong!');
        }
    }


    public function getProduct(Request $request)
    {

        

        if($request->ajax())
        {
            $returnCart = session()->get('returnCart');
            $id =  $request->product_id;
            $project_id =  $request->project_id;
            
            
                
            
                
                
            $product =  Product::join('sells_details','sells_details.product_id', 'products.id')
                                    ->join('sells','sells_details.sells_id', 'sells.id')
                                    ->where('products.id',$id)
                                    ->where('sells.project_id',$project_id)
                                    ->where('sells.type','sell')
                                    ->select('products.id', 'products.name','products.unit_id','products.unit_price',
                                        DB::raw('SUM(sells_details.quantity - sells_details.return_quantity) as qty'))
                                    ->groupby('products.id', 'products.name','products.unit_id','products.unit_price')
                                    ->having('qty', '>',0)
                                    ->first();
       
            if($product){
                        if(isset($returnCart[$id]))
                        {
                            $returnCart[$id]['quantity']++;
                            $returnCart[$id]['total_price'] = $returnCart[$id]['quantity'] * $returnCart[$id]['unit_price'];
                            $returnCart[$id]['qty_available'] =$product->qty;
                            session()->put('returnCart', $returnCart);
                        }else{
                            $returnCart[$id] = [
                            'product_id' => $id,
                            'product_name' => $product->name,
                            'product_unit' => $product->unit->name,
                            'description' => '',
                            'quantity' =>1,
                            'qty_available' => $product->qty,
                            'unit_price' => $product->unit_price,
                            'total_price' => $product->unit_price
                            ];
                            session()->put('returnCart', $returnCart);
                        }

            }else{
               return response()->json([
                    'status' => false,
                    'msg' => 'Stock Not Available'
                ]); 
            }
        }
        $view = view('backend.admin.stock_return.stock_return_cart')->render();
        return response()->json([
            'status' => true,
            'data' => $view
        ]);
    }


    public function cartUpdate(Request $request)
    {
        if($request->ajax())
        {
            $returnCart = session()->get('returnCart');
            $customer_id=request('customer_id');
            if($request->id || $request->qty || $request->unit_price)
            {
                $id =  $request->id;
                $qty =  $request->qty;
                $unit_price =  $request->unit_price;
               

                $product =  Product::join('sells_details','sells_details.product_id', 'products.id')
                                    ->join('sells','sells_details.sells_id', 'sells.id')
                                    ->where('products.id',$id)
                                    ->where('sells.type','sell')
                                    ->select('products.id', 'products.name','products.unit_id',
                                       DB::raw('SUM(sells_details.quantity - sells_details.return_quantity) as qty'))
                                    ->groupby('products.id', 'products.name','products.unit_id')
                                    ->having('qty', '>',0)
                                    ->first();

                if(isset($returnCart[$id]))
                {  
                       $returnCart[$id]['quantity'] = $qty;
                        $returnCart[$id]['unit_price'] = $unit_price;
                        $returnCart[$id]['total_price'] = $qty * $unit_price;
                        $returnCart[$id]['qty_available'] =$product->qty;
                        session()->put('returnCart', $returnCart);

                   
                }else{
                    $returnCart[$id] = [
                    'product_id' => $id,
                    'product_name' => $product->name,
                    'product_unit' => $product->unit->name,
                    'description' => '',
                    'quantity' =>1,
                    'unit_price' => 0,
                    'total_price' => 0, 
                    'qty_available' => $product->qty,
                    ];
                    session()->put('returnCart', $returnCart);
                }
            }
        }
       $view = view('backend.admin.stock_return.stock_return_cart')->render();
        return response()->json([
            'status' => true,
            'data' => $view
        ]);
    }



    public function removeSingleCart(Request $request)
    {
        $returnCart = session()->has('returnCart') ? session()->get('returnCart'):[];

        unset($returnCart[$request->input('product_id')]);
        
        session()->put('returnCart', $returnCart);

        $view = view('backend.admin.stock_return.stock_return_cart')->render();
        return response()->json([
            'status' => true,
            'data' => $view
        ]);
    }

    public function removeAllCart(Request $request)
    {
        $returnCart = session()->has('returnCart') ? session()->get('returnCart')  :[];
        $view = view('backend.admin.stock_return.stock_return_cart')->render();
        if($returnCart)
        {
            return response()->json([
                'status' => true,
                'data' => $view
            ]);
        }else{
            return response()->json([
                'status' => false,
                'data' => $view
            ]);
        }
    }



    public function customerWiseProduct(){

        $customer_id=request('customer_id');
        
        $product =  Product::join('sells_details','sells_details.product_id', 'products.id')
                        ->join('sells','sells_details.sells_id', 'sells.id')
                        ->where('sells.customer_id',$customer_id)
                        ->where('sells.type','sell')
             
                        ->select('products.id', 'products.name',
                            DB::raw('SUM(sells_details.quantity - sells_details.return_quantity) as qty'))
                        ->groupby('products.id', 'products.name')
                        ->having('qty', '>',0)
                        ->orderBy('name','asc')
                        ->pluck("name","id");
                        
        

        return json_encode($product);
    }






}
