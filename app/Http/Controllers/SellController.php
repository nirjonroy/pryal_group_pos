<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Backend\Admin\Payment\Payment_method;
use App\Model\Backend\Admin\Product\Product;
use App\Model\Backend\Admin\Project\Project;
use App\Customer;
use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Purchase\Purchase_details;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
use App\Model\Backend\Admin\Supplier\Supplier;
use App\Store;
use App\Sell_detail;
use App\Sell; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Utils\QuantityManage;
use App\QuantityStore;
class SellController extends Controller 
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
        $data['cus'] =Customer::orderBy('name','asc')->get();
        
        // $data['sups'] =Supplier::orderBy('name','asc')->get();
        $com =Company::orderby('name','asc');
                if(request()->type_id !=''){
                    $com->where('type_id',request()->type_id);
                }
        $data['coms']=$com->get();
        $data['cust'] =Customer::orderBy('name','asc')->get();
        $query=Sell::with('projects','projects.companies', 'payments', 'customer')->where('type','Sell');
        
        //  $query->where('transction_id', 'id')
        //  ->select(DB::raw("SUM(total_price) as paytotal"));
        // dd($query);
        
        // $query->SUM(total_price)->where('transction_id', 'id');
        if(request()->date_start and request()->date_end !=''){
            $query->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
        }

        if(request()->project_id !=''){
            $query->where('project_id',request()->project_id);
        }

        if(request()->company_id!=''){
            $query->where('company_id',request()->company_id);
        }

        if(request()->customer_id !=''){
            $query->where('customer_id',request()->customer_id );
        }

        

        if(request()->alldata){
           $data['sells']=$query->latest()->paginate(2000);
          }elseif(request()->date_start and request()->date_end !=''){
              $data['sells']=$query->latest()->paginate(2000);
          }else{
           $data['sells']=$query->latest()->paginate(30);

          }
       


       $query_2=Sell::with('payments')->where('type','sell');
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
        $data['total_summery']=$query_2->sum('total_price');
                
        // dd($data);
        return view('backend.admin.sell.index', $data);
    }

    
    public function create(Request $request){
        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();
        $data['quantity'] = QuantityStore::where('quantity_available', '>', 0)->get();
        $data['customer'] = Customer::orderby('name','asc')->whereNull('deleted_at')->get();
        $data['stores'] =DB::table('stores')
                                ->get();      
                                     
        return view('backend.admin.sell.create', $data);
    }

     
    public function ProductDefaultLoading(Request $request)
    {
        $saleCart = session()->has('saleCart') ? session()->put('saleCart',[])  :[];
        $view = view('backend.admin.sell.createSaleAddToCart')->render();
        if($saleCart)
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

    public function addProductsell(Request $request)
    {

        if($request->ajax())
        {
            $saleCart = session()->get('saleCart');
            $id =  $request->product_id;
            $store_id =  $request->store_id;
            if(($id) and($store_id))
            {
                
                $product =  Product::leftjoin('quantity_stores','quantity_stores.product_id', 'products.id')
                                        ->where('products.id',$id)
                                        ->where('quantity_stores.store_id',$store_id)
                                        ->where('quantity_stores.quantity_available', '>', 0)
                                        ->select('products.id', 'products.name','products.unit_id','products.unit_price',
                                            DB::raw('SUM(quantity_stores.quantity_available) as qty'))
                                        ->where('quantity_stores.quantity_available','>',0)
                                        ->groupby('products.id', 'products.name','products.unit_id','products.unit_price')
                                        ->first();
                if(isset($saleCart[$id]))
                {
                    $saleCart[$id]['quantity']++;
                    $saleCart[$id]['total_price'] = $saleCart[$id]['quantity'] * $saleCart[$id]['unit_price'];
                    $saleCart[$id]['qty_available'] =$product->qty;
                    session()->put('saleCart', $saleCart);
                }else{
                    $saleCart[$id] = [
                    'product_id' => $id,
                    'product_name' => $product->name,
                    'product_unit' => $product->unit->name,
                    'description' => '',
                    'quantity' =>1,
                    'qty_available' => $product->qty,
                    'unit_price' => $product->unit_price,
                    'total_price' => $product->unit_price
                    ];
                    session()->put('saleCart', $saleCart);
                }
            }else{
               return response()->json([
                    'status' => false,
                    'msg' => 'Stock Not Available'
                ]); 
            }
        }
        $view = view('backend.admin.sell.createSaleAddToCart')->render();
        return response()->json([
            'status' => true,
            'data' => $view
        ]);
    }

    public function ProductRemoveSingle(Request $request)
    {
        $saleCart = session()->has('saleCart') ? session()->get('saleCart'):[];
        unset($saleCart[$request->input('product_id')]);
        session(['saleCart'=>$saleCart]);
        $view = view('backend.admin.sell.createSaleAddToCart')->render();
        return response()->json([
            'status' => true,
            'data' => $view
        ]);
    }

    public function sellProductRemoveAll(Request $request)
    {
        $saleCart = session()->has('saleCart') ? session()->get('saleCart')  :[];
        $view = view('backend.admin.sell.createSaleAddToCart')->render();
        if($saleCart)
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

    public function sellUpdateQtyPrice(Request $request)
    {
        if($request->ajax())
        {
            $saleCart = session()->get('saleCart');
            if($request->id || $request->qty || $request->unit_price)
            {
                $id =  $request->id;
                $qty =  $request->qty;
                $unit_price =  $request->unit_price;
                $product =   Product::join('quantity_stores','quantity_stores.product_id', 'products.id')
                                ->where('products.id',$id)
                                ->select('products.id', 'products.name','products.unit_id',
                                    DB::raw('SUM(quantity_stores.quantity_available) as qty'))
                                ->groupby('products.id', 'products.name','products.unit_id')
                                ->first();
                if(isset($saleCart[$id]))
                {
                    //$saleCart[$id]['quantity']++ ;
                   // $saleCart[$id]['total_price'] = $saleCart[$id]['quantity'] * $saleCart[$id]['unit_price'];
                   
                       $saleCart[$id]['quantity'] = $qty;
                        $saleCart[$id]['unit_price'] = $unit_price;
                        $saleCart[$id]['total_price'] = $qty * $unit_price;
                        $saleCart[$id]['qty_available'] =$product->qty;
                        session()->put('saleCart', $saleCart);

                   
                }else{
                    $saleCart[$id] = [
                    'product_id' => $id,
                    'product_name' => $product->name,
                    'product_unit' => $product->unit->name,
                    'description' => '',
                    'quantity' =>1,
                    'unit_price' => 0,
                    'total_price' => 0, 
                    'qty_available' => $product->qty,
                    ];
                    session()->put('saleCart', $saleCart);
                }
            }
        }
        $view = view('backend.admin.sell.createSaleAddToCart')->render();
        return response()->json([
            'status' => true,
            'data' => $view
        ]);
    }


    public function store(Request $request)
    {
     
        $cash_hand = (purchaseInHand($request->supplier_id) + $request->totalProductPrice);

        DB::beginTransaction();
        try
        {
            $input = $request->except('_token');
            $validator = Validator::make($input,[
                    'customer_id' => 'required',
                    
                    'product_id' => 'required',
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
                
                $sell->type = 'sell';
                $sell->store_id = $request->store_id;
                $sell->customer_id = $request->customer_id;
                $sell->description = $request->description;
                $sell->total_quantity = $request->totalProductQuantity;
                $sell->total_price = $request->totalProductPrice;
                $sell->cash_hand = $cash_hand;
                $sell->user_id = Auth::user()->id;
                $sell->created_at = $_POST['date'].' '.date('h:i:s');
                $save =  $sell->save();
                $sells_id = $sell->id;
                $store_id =$sell->store_id;
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

                        $this->quantityManage->decreaseQuantity($product_id,$store_id,$quantity);
                    }
                }
        

                    

                if($request->payment_amount &&  $request->payment_amount > 0)
                {
                    $payment  = new Sell();
                    $payment->customer_id = $request->customer_id;
                    $payment->transction_id = $sells_id;
                    $payment->invoice_no = 'pay';
                    $payment->customer_id = $request->customer_id;
                    $payment->type = 'payment';
                    $payment->total_price = $request->payment_amount;
                    $payment->method_id = $request->payment_method_id;
                    
                    $payment->user_id = Auth::user()->id;
                    $payment->cash_hand = $cash_hand - $request->payment_amount;
                    $payment->save();
                }
                // dd($payment);
                
                $this->quantityManage->sellStatusUpdate($sells_id);
                
                DB::commit();
                if($save)
                {
                    session(['saleCart' => []]);
                    return redirect()->action('SellController@index')->with('success','New Sell is Created successfully!');
                }
                else{
                    return redirect()->back()->with('error','Sell is Not Created!');
                }
        }
        catch (\Throwable $e)
        {
            DB::rollback();
            throw $e;
            return redirect()->back()->with('error','Somethings went to wrong!');
        }


    }


    public function show($id)
    {
       
        $data['sell'] = Sell::with('sellDetails', 'customer')->findOrFail($id);
        $data['pay'] = Sell::with('payments', 'method', 'users')->where('transction_id', $id )->get();
        // dd($data);
        return view('backend.admin.sell.show',$data);
        
    }


    public function edit($id)
    {
       

        $sell=Sell::with('sellDetails', 'payments')->findOrFail($id);
        // dd($sell);
        $project_company_id=$sell->company_id;
        

        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();
        $data['sell_pay'] = sell::with('payments')->where('transction_id', $id)->get();
        $data['projects'] = Project::where('company_id',$project_company_id)->whereNull('deleted_at')->get();
        $data['sell'] = $sell;
         $data['stores'] =Store::all();
        //  dd($data);
        return view('backend.admin.sell.edit',$data); 






    }

    
    public function update(Request $request, $id)
    {
        $project=Project::select('company_id')->find($request->project_id);
        DB::beginTransaction();
        try
        {
            $input = $request->except('_token');
            $validator = Validator::make($input,[
                    
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
                
                

        
                    $sell->project_id = $request->project_id;
                
                    $sell->description = $request->description;
                    $sell->total_quantity = $request->totalProductQuantity;
                    $sell->total_price = $request->totalProductPrice;
                    $sell->cash_hand=0;
                    $sell->user_id = Auth::user()->id;
                    $sell->created_at = $_POST['date'].' '.date('h:i:s');
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

                                $this->quantityManage->decreaseQuantity($product_id,$store_id,$new_quantity,$sell_detail->quantity);

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

                                $this->quantityManage->decreaseQuantity($product_id,$store_id,$new_quantity);
                            }
                            $sell_detail->save();

                             $edit_lines[]=$sell_detail->id;
                        }
                    }

                $delete_data=$sell->sellDetails()->whereNOtIn('id',$edit_lines)->get()->pluck('id')->toArray();

                if (!empty($delete_data)) {

                    $items=Sell_detail::whereIn('id',$delete_data)->get();

                    foreach ($items as $key => $v) {
                       $this->quantityManage->updateQuantity($v->product_id,$sell->store_id,$v->quantity);
                    }
                    Sell_detail::whereIn('id',$delete_data)->delete();
                }
                
                $this->quantityManage->sellStatusUpdate($id);
               
                DB::commit();
                if($save)
                {
                    session(['saleCart' => []]);
                    return redirect()->action('SellController@index')->with('success','Transfer is Update successfully!');
                }
                else{
                    return redirect()->back()->with('error','Purchese is Not Created!');
                }
        }
        catch (\Throwable $e)
        {
            DB::rollback();
            throw $e;
            return redirect()->back()->with('error','Somethings weng to wrong!');
        }
    }
    

    public function destroy_sell( $id){
        
    DB::beginTransaction();
    try
        {
        $sell=Sell::find($id);


        $get_up_data = Sell::where('customer_id', $sell->customer_id)
                             ->where('id', '>', $id)
                             ->get(); 
                                     

        if ($sell->type== 'sell') {
             if (!empty($get_up_data)) {
                 foreach ($get_up_data as  $value) {
                    $value->cash_hand = $value->cash_hand - $sell->total_price;
                    $value->save();
                 }
             }
         }elseif($sell->type=='payment') {
             if (!empty($get_up_data)) {
                 foreach ($get_up_data as  $value) {
                    $value->cash_hand = $value->cash_hand + $sell->total_price;
                    $value->save();
                 }
             }
         }
        
        foreach ($sell->sellDetails as $v) {
            $this->quantityManage->updateQuantity($v->product_id,$sell->store_id,$v->quantity);
        }
        $sell->sellDetails()->delete();
        $sell->delete();
        
        if($sell->payments()->count()){
            $sell->payments()->delete();
        }

        DB::commit();
        return redirect()->back()->with('success','Sell Deleted successfully!');
    }   catch (\Throwable $e)
        {
            // DB::rollback();
            throw $e;
            return redirect()->back()->with('error','Somethings weng to wrong!');
        }
    }



    public function storeWiseProduct(){

        $store_id=request('store_id');
        
        $product =  Product::join('quantity_stores','quantity_stores.product_id', 'products.id')
                        ->where('quantity_stores.store_id',$store_id)
                        ->select('products.id', 'products.name',
                            DB::raw('SUM(quantity_stores.quantity_available) as qty'))
                        ->having('qty','>',0)
                        ->groupby('products.id', 'products.name')
                        ->orderBy('name','asc')
                        ->pluck("name","id");
        return json_encode($product);
        
    }

    public function sellPayment($id){
 
        $sell =Sell::with('payments','customer', 'method')->find($id);
        $payment_methods = Payment_method::whereNull('deleted_at')->get();
        
        
        return view('backend.admin.sell.payment', compact('sell', 'payment_methods'));
    }
    
    public function update_payment(Request $request, $id){
         
          
            
     
            $date=request('date').' '.date('h:i:s');
            $sell  =Sell::find($id);
            $payment=new Sell();
            $payment->transction_id = $sell->id;
            $payment->customer_id = $sell->customer_id;
            $payment->invoice_no = 'pay';
            $payment->type = 'payment';
            $payment->description = request('note');
            $payment->total_price = request('payment_amount');
            $payment->method_id =request('payment_method_id');
            $payment->user_id = Auth::user()->id;
            $payment->created_at = $date;
            $payment->save();
            $this->quantityManage->sellStatusUpdate($sell->id);
            
        return redirect('sell')->with('success', 'Payment Update Success');
    }
    
    
    public function purchase_payment($id){
 
        $purchase =Purchase::with('suppliers', 'payments')->find($id);
        
        //  dd($purchase);
        
        return view('backend.admin.stock.payment', compact('purchase'));
    }

}
