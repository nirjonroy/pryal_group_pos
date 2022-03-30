<?php

namespace App\Http\Controllers\Backend\Admin\Purchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Backend\Admin\Payment\Payment_method;
use App\Model\Backend\Admin\Product\Product;
use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Purchase\Purchase_details;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
use App\Model\Backend\Admin\Supplier\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Utils\QuantityManage;
use App\Store;



class AddStockController extends Controller
{
    protected $quantityManage;
    public function __construct(QuantityManage $quantity_manage)
    {
        $this->quantityManage=$quantity_manage;
        
    }

     public function index()
    {
        if(!auth()->user()->can('purchase.index')){
            abort(403, 'Unauthorized action.');
        }

        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();
        $pro =Project::orderby('name','asc');
                if(request()->status !=''){
                    $pro->where('working_status',request()->status);
                }

                if(request()->company_id!=''){
                    $pro->where('company_id',request()->company_id);
                }
        $data['projects']=$pro->get();
        
        $data['sups'] =Supplier::orderBy('name','asc')->get();
        $com =Company::orderby('name','asc');
                if(request()->type_id !=''){
                    $com->where('type_id',request()->type_id);
                }
        $data['coms']=$com->get();

        $query=Purchase::with('store', 'payments', 'purchase')->where('type','stock');
        
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

        

        if(request()->alldata){
           $data['purchases']=$query->latest()->paginate(2000);
          }elseif(request()->date_start and request()->date_end !=''){
              $data['purchases']=$query->latest()->paginate(2000);
          }else{
           $data['purchases']=$query->latest()->paginate(30);

          }
       


       $query_2=Purchase::where('type','stock');
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
        return view('backend.admin.stock.index',$data);
    }


    public function create()
    {
        if(!auth()->user()->can('purchase.create')){
            abort(403, 'Unauthorized action.');
        }

        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();
        $data['products'] = Product::orderby('name','asc')->whereNull('deleted_at')->get();
        $data['suppliers'] = Supplier::orderby('name','asc')->whereNull('deleted_at')->get();
        $data['stores'] = Store::orderby('name')->get();

        return view('backend.admin.stock.create',$data);
    }



    public function edit($id)
    {
        if(!auth()->user()->can('purchase.index')){
            abort(403, 'Unauthorized action.');
        }

        $purchase=Purchase::with('purchaseDetails', 'payments')->findOrFail($id);
        $project_company_id=$purchase->company_id;
        $supplier_type_id=$purchase->suppliers->type_id;
        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();
        $data['products'] = Product::whereNull('deleted_at')->get();
        $data['stock_pay'] = Purchase::with('payments')->where('transction_id', $id)->get();
        $data['suppliers'] = Supplier::where('type_id',$supplier_type_id)->whereNull('deleted_at')->get();
        $data['stores'] = Store::orderby('name')->get();
        $data['purchase'] = $purchase;
       
        // dd($data);
        
        
        return view('backend.admin.stock.edit',$data);
    }



    public function store(Request $request)
    {
        if(!auth()->user()->can('purchase.create')){
            abort(403, 'Unauthorized action.');
        }

        
        $cash_hand = (purchaseInHand($request->supplier_id) + $request->totalProductPrice);

        DB::beginTransaction();
        try
        {
            $input = $request->except('_token');
            $validator = Validator::make($input,[
                    'supplier_id' => 'required',
                    'store_id' => '',
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
                

                $purchase  = new Purchase();
                $purchase->invoice_no = $invoice_no;
                $purchase->type = 'stock';
                $purchase->store_id = $request->store_id;
                $purchase->supplier_id = $request->supplier_id;
                $purchase->description = $request->description;
                $purchase->total_quantity = $request->totalProductQuantity;
                $purchase->total_price = $request->totalProductPrice;
                $purchase->batch_no = $request->batch_no;
                $purchase->car_rent = $request->car_rent;
                $purchase->labour_cost = $request->labour_cost;
                $purchase->bosta_cost = $request->bosta_cost;
                $purchase->other_cost = $request->other_cost;
                $purchase->cash_hand = $cash_hand;
                $purchase->user_id = Auth::user()->id;
                $purchase->created_at = $_POST['date'].' '.date('h:i:s');
                $save =  $purchase->save();
                $purchase_id = $purchase->id;
                $store_id =$purchase->store_id;
                // dd($purchase);
                if($request->product_id)
                {
                    foreach($request->product_id as $key=> $product)
                    {
                        $product_id=$request->product_id[$key];
                        $quantity=$request->quantity[$key];
                        $purchase_detail  = new Purchase_details();
                        $purchase_detail->invoice_no = $invoice_no;
                        $purchase_detail->purchase_id = $purchase_id;
                        $purchase_detail->product_id = $product_id;
                        $purchase_detail->quantity = $quantity;
                        $purchase_detail->unit_price = $request->sale_unit_price[$key];
                        $purchase_detail->total_price = $request->quantity[$key] * $request->sale_unit_price[$key];
                        $purchase_detail->description = $request->description;
                        $purchase_detail->created_by = Auth::user()->id;
                        $purchase_detail->created_at = $_POST['date'].' '.date('h:i:s');
                        $purchase_detail->save();
                        $this->quantityManage->PriceUpdate($product_id);
                        $this->quantityManage->updateQuantity($product_id,$store_id,$quantity);
                    }
                }
                if($request->payment_amount &&  $request->payment_amount > 0)
                {
                    $payment  = new Purchase();
                    $payment->supplier_id = $request->supplier_id;
                    $payment->transction_id = $purchase_id;
                    $payment->invoice_no = 'pay';
                    $payment->type = 'stock_payment';
                    $payment->total_price = $request->payment_amount;
                    $payment->method_id = $request->payment_method_id;
                    $payment->user_id = Auth::user()->id;
                    $payment->cash_hand = $cash_hand - $request->payment_amount;
                    $payment->created_at = $_POST['date'].' '.date('h:i:s');
                    $payment->save();
                }
                $this->quantityManage->purchaseStatusUpdate($purchase_id);
                DB::commit();
                if($save)
                {
                    session(['saleCart' => []]);
                    return redirect()->route('admin.stocks.index')->with('success','Stock Add Created successfully!');
                }
                else{
                    return redirect()->back()->with('error','Stock Add Is  Not Created!');
                }
        }
        catch (\Throwable $e)
        {
            DB::rollback();
            throw $e;
            return redirect()->back()->with('error','Somethings weng to wrong!');
        }
    }



    public function update(Request $request, $id)
    {
        if(!auth()->user()->can('purchase.index')){
            abort(403, 'Unauthorized action.');
        }




        $project=Project::select('company_id')->find($request->project_id);
        DB::beginTransaction();
        try
        {
            $input = $request->except('_token');
            $validator = Validator::make($input,[
                    'supplier_id' => 'required',
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

                $invoice_no = mt_rand(10000000, 99999999);

                $purchase  = Purchase::find($id);
                $store_id=$purchase->store_id;
                $get_up_data = Purchase::where('supplier_id', $purchase->supplier_id)
                                         ->where('id', '>', $id)
                                         ->get(); 

                $total_price = $purchase->total_price;
                
                

                if ($request->totalProductPrice >  $total_price) 
                {


                    
                $greter_price = $request->totalProductPrice - $total_price;
                    $purchase->supplier_id = $request->supplier_id;
                    $purchase->description = $request->description;
                    $purchase->total_quantity = $request->totalProductQuantity;
                    $purchase->total_price = $request->totalProductPrice;
                    $purchase->cash_hand=$purchase->cash_hand + $greter_price;
                    $purchase->user_id = Auth::user()->id;
                    $purchase->created_at = $_POST['date'].' '.date('h:i:s');
                    $save =  $purchase->save();

                    
                    
                    if(isset($request->product_id))
                    {
                        $edit_lines=[];
                        foreach($request->product_id as $key=> $product)
                        {
                     
                            $product_id=$request->product_id[$key];
                            $new_quantity=$request->quantity[$key];

                            if (isset($request->details_id[$key])) {
                                $purchase_detail=Purchase_details::find($request->details_id[$key]);

                                $this->quantityManage->updateQuantity($product_id,$store_id,$new_quantity,$purchase_detail->quantity);

                                $purchase_detail->unit_price = $request->sale_unit_price[$key];
                                $purchase_detail->quantity = $new_quantity;
                                $edit_lines[]=$request->details_id[$key];

                                
                            }else{

                               $purchase_detail  = new Purchase_details();
                                $purchase_detail->invoice_no = $purchase->invoice_no;
                                $purchase_detail->purchase_id = $id;
                                $purchase_detail->product_id = $request->product_id[$key];
                                $purchase_detail->quantity = $new_quantity;
                                $purchase_detail->unit_price = $request->sale_unit_price[$key];
                                $purchase_detail->total_price = $request->quantity[$key] * $request->sale_unit_price[$key];
                                $purchase_detail->description = $request->description;

                                $purchase_detail->created_by = Auth::user()->id;

                                $this->quantityManage->updateQuantity($product_id,$store_id,$new_quantity);
                            }
                            $purchase_detail->save();
                            
                           $this->quantityManage->purchaseStatusUpdate($id);
                        }


                    }
                    

                     if (!empty($get_up_data)) {
                       foreach ($get_up_data as $data) {
                           $data->cash_hand = $data->cash_hand + $greter_price;
                           $data->save();
                       }
                     }
                }

                elseif ($request->totalProductPrice <  $total_price)
                 {
                     $less_price = $total_price - $request->totalProductPrice ;
                    $purchase->supplier_id = $request->supplier_id;
                    $purchase->description = $request->description;
                    $purchase->total_quantity = $request->totalProductQuantity;
                    $purchase->total_price = $request->totalProductPrice;
                    $purchase->cash_hand=$purchase->cash_hand - $less_price;
                    $purchase->user_id = Auth::user()->id;
                    $purchase->created_at = $_POST['date'].' '.date('h:i:s');
                    $save =  $purchase->save();
                    

                    if(isset($request->product_id))
                    {
                        $edit_lines=[];
                        foreach($request->product_id as $key=> $product)
                        {
                     
                            $product_id=$request->product_id[$key];
                            $new_quantity=$request->quantity[$key];

                            if (isset($request->details_id[$key])) {
                                $purchase_detail=Purchase_details::find($request->details_id[$key]);
                                $this->quantityManage->updateQuantity($product_id,$store_id,$new_quantity,$purchase_detail->quantity);
                                
                                $purchase_detail->unit_price = $request->sale_unit_price[$key];
                                $purchase_detail->quantity = $new_quantity;
                                $edit_lines[]=$request->details_id[$key];

                                
                            }else{

                               $purchase_detail  = new Purchase_details();
                                $purchase_detail->invoice_no = $purchase->invoice_no;
                                $purchase_detail->purchase_id = $id;
                                $purchase_detail->product_id = $request->product_id[$key];
                                $purchase_detail->quantity = $new_quantity;
                                $purchase_detail->unit_price = $request->sale_unit_price[$key];
                                $purchase_detail->total_price = $request->quantity[$key] * $request->sale_unit_price[$key];
                                $purchase_detail->description = $request->description;

                                $purchase_detail->created_by = Auth::user()->id;

                                $this->quantityManage->updateQuantity($product_id,$store_id,$new_quantity);
                            }
                            $purchase_detail->save();
                            $this->quantityManage->PriceUpdate($product_id);
                        }
                    }



                     if (!empty($get_up_data)) {
                       foreach ($get_up_data as $data) {
                           $data->cash_hand = $data->cash_hand - $less_price;
                           $data->save();
                       }
                     }
                }
                elseif ($request->totalProductPrice == $total_price) 
                {
                    $purchase->supplier_id = $request->supplier_id;
                    $purchase->description = $request->description;
                    $purchase->total_quantity = $request->totalProductQuantity;
                    $purchase->total_price = $request->totalProductPrice;
                    $purchase->cash_hand=$purchase->cash_hand;
                    $purchase->user_id = Auth::user()->id;
                    $purchase->created_at = $_POST['date'].' '.date('h:i:s');
                    $save =  $purchase->save();
                    

                    if(isset($request->product_id))
                    {
                        $edit_lines=[];
                        foreach($request->product_id as $key=> $product)
                        {
                            
                            $product_id=$request->product_id[$key];
                            $new_quantity=$request->quantity[$key];

                            if (isset($request->details_id[$key])) {
                                $purchase_detail=Purchase_details::find($request->details_id[$key]);
                                $this->quantityManage->updateQuantity($product_id,$store_id,$new_quantity,$purchase_detail->quantity);

                                $purchase_detail->quantity = $new_quantity;
                                $purchase_detail->unit_price = $request->sale_unit_price[$key];
                                $edit_lines[]=$request->details_id[$key];
                            }else{

                               $purchase_detail  = new Purchase_details();
                                $purchase_detail->invoice_no = $purchase->invoice_no;
                                $purchase_detail->purchase_id = $id;
                                $purchase_detail->product_id = $request->product_id[$key];
                                $purchase_detail->quantity = $new_quantity;
                                $purchase_detail->unit_price = $request->sale_unit_price[$key];
                                $purchase_detail->total_price = $request->quantity[$key] * $request->sale_unit_price[$key];
                                $purchase_detail->description = $request->description;

                                $purchase_detail->created_by = Auth::user()->id;

                                $this->quantityManage->updateQuantity($product_id,$store_id,$new_quantity);
                            }
                            $purchase_detail->save();
                            
                            $this->quantityManage->PriceUpdate($product_id);
                        }
                    }


                }



                $delete_data=$purchase->purchaseDetails()->whereNOtIn('id',$edit_lines)->get()->pluck('id')->toArray();

                if (!empty($delete_data)) {

                    $items=Purchase_details::whereIn('id',$delete_data)->get();

                    foreach ($items as $key => $v) {
                       $this->quantityManage->decreaseQuantity($v->product_id,$purchase->store_id,$v->quantity);
                    }
                    
                    
                    
                    Purchase_details::whereIn('id',$delete_data)->delete();
                }
               
                DB::commit();
                if($save)
                {
                    session(['saleCart' => []]);
                    return redirect()->route('admin.stocks.index')->with('success','Stock Add Updated successfully!');
                }
                else{
                    return redirect()->back()->with('error','Purchese is Not Created!');
                }
        }
        catch (\Throwable $e)
        {
            // DB::rollback();
            throw $e;
            return redirect()->back()->with('error','Somethings weng to wrong!');
        }
    }



    public function destroy($id)
    {
        if(!auth()->user()->can('purchase.index')){
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try
            {
            $purchase=Purchase::find($id);

            foreach ($purchase->purchaseDetails as $v) {
                $this->quantityManage->decreaseQuantity($v->product_id,$purchase->store_id,$v->quantity);
            }
            $purchase->purchaseDetails()->delete();
            $purchase->delete();

            DB::commit();
            return redirect()->back()->with('success','Purchase Deleted successfully!');
        }   catch (\Throwable $e)
            {
                // DB::rollback();
                throw $e;
                return redirect()->back()->with('error','Somethings weng to wrong!');
            }
    }
    
    
    public function show($id)
    {
        if(!auth()->user()->can('purchase.index')){
            abort(403, 'Unauthorized action.');
        }
        
        $data['purchase'] = Purchase::with('purchaseDetails')->findOrFail($id);
        
        $data['pay'] = Purchase::with('payments', 'method', 'users')->where('transction_id', $id )->get();
        $data['costs'] = Purchase::where('id', $id)->first();
        // dd($data);
        return view('backend.admin.stock.show',$data);
    }
    
    
     public function purchasePayments(Request $request, $id){
        
        
        
        $purchase =Purchase::with('suppliers', 'payments')->find($id);
        $payment_methods = Payment_method::whereNull('deleted_at')->get();
        //  dd($purchase);
        
        return view('backend.admin.stock.payment', compact('purchase', 'payment_methods'));
    }
    
    
    
    public function update_payment(Request $request, $id){
             
             
            
            
            $date=request('date').' '.date('h:i:s');
            $sell  =Purchase::find($id);
            $payment=new Purchase();
            $payment->transction_id = $sell->id;
            $payment->supplier_id = $sell->supplier_id;
            $payment->invoice_no = 'pay';
            $payment->type = 'payment';
            $payment->description = request('note');
            $payment->total_price = request('payment_amount');
            $payment->method_id =request('payment_method_id');
            $payment->user_id = Auth::user()->id;
            $payment->created_at = $date;
            $payment->save();
            $this->quantityManage->purchaseStatusUpdate($sell->id);
            
        return redirect()->back()->with('success', 'Payment Update Success');
    }



   





}
