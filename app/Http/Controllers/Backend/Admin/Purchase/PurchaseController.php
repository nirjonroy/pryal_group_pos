<?php

namespace App\Http\Controllers\Backend\Admin\Purchase;

use App\Http\Controllers\Controller;
use App\Model\Backend\Admin\Payment\Payment_method;
use App\Model\Backend\Admin\Product\Product;
use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\Model\Backend\Admin\Company\Company;
use App\CompanyType;
use App\Model\Backend\Admin\Purchase\Purchase_details;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
use App\Model\Backend\Admin\Supplier\Supplier;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Utils\QuantityManage;

class PurchaseController extends Controller
{
    protected $quantityManage;
    public function __construct(QuantityManage $quantity_manage)
    {
        $this->quantityManage=$quantity_manage;
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $query=Purchase::with('projects','projects.companies')->where('type','purchase');
        
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
       


       $query_2=Purchase::where('type','purchase');
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
        
        return view('backend.admin.purchase.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        
        $company_id = CompanyType::all();
        $data['companies'] = Company::all();    
        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();
        $data['products'] = Product::orderby('name','asc')->whereNull('deleted_at')->get();
        $data['projects'] = Project::where('company_id')->orderby('name','asc')->get();
        $data['suppliers'] = Supplier::orderby('name','asc')->whereNull('deleted_at')->get();
        $data['stores'] = Store::orderby('name')->get();
        
        return view('backend.admin.purchase.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addToCartProduct(Request $request)
    {

        if($request->ajax())
        {
            $saleCart = session()->get('saleCart');
            if($request->product_id)
            {
                $id =  $request->product_id;
                $product =  Product::findOrFail($id);
                if(isset($saleCart[$id]))
                {
                    $saleCart[$id]['quantity']++;
                    $saleCart[$id]['total_price'] = $saleCart[$id]['quantity'] * $saleCart[$id]['unit_price'];
                    session()->put('saleCart', $saleCart);
                }else{
                    $saleCart[$id] = [
                    'product_id' => $id,
                    'product_name' => $product->name,
                    'product_unit' => $product->unit->name,
                    'description' => '',
                    'quantity' =>1,
                    'unit_price' => $product->unit_price,
                    'total_price' => $product->unit_price
                    ];
                    session()->put('saleCart', $saleCart);
                }
            }
        }
        $view = view('backend.admin.purchase.createSaleAddToCart')->render();
        return response()->json([
            'status' => true,
            'data' => $view
        ]);
    }


    public function addToCartProductUpdateQtyPrice(Request $request)
    {
        if($request->ajax())
        {
            $saleCart = session()->get('saleCart');
            if($request->id || $request->qty || $request->unit_price)
            {
                $id =  $request->id;
                $qty =  $request->qty;
                $unit_price =  $request->unit_price;
                $product =  Product::findOrFail($id);
                if(isset($saleCart[$id]))
                {
                    //$saleCart[$id]['quantity']++ ;
                   // $saleCart[$id]['total_price'] = $saleCart[$id]['quantity'] * $saleCart[$id]['unit_price'];
                    $saleCart[$id]['quantity'] = $qty;
                    $saleCart[$id]['unit_price'] = $unit_price;
                    $saleCart[$id]['total_price'] = $qty * $unit_price;
                    session()->put('saleCart', $saleCart);
                }else{
                    $saleCart[$id] = [
                    'product_id' => $id,
                    'product_name' => $product->name,
                    'product_unit' => $product->unit->name,
                    'description' => '',
                    'quantity' =>1,
                    'unit_price' => 0,
                    'total_price' => 0
                    ];
                    session()->put('saleCart', $saleCart);
                }
            }
        }
        $view = view('backend.admin.purchase.createSaleAddToCart')->render();
        return response()->json([
            'status' => true,
            'data' => $view
        ]);
    }

    public function addToCartProductRemoveAll()
    {
        session(['saleCart' => []]);
        return redirect()->back();
    }

    public function addToCartProductRemoveSingle(Request $request)
    {
        $saleCart = session()->has('saleCart') ? session()->get('saleCart')  :[];
		unset($saleCart[$request->input('product_id')]);
        session(['saleCart'=>$saleCart]);
        $view = view('backend.admin.purchase.createSaleAddToCart')->render();
        return response()->json([
            'status' => true,
            'data' => $view
        ]);
    }
    public function addToCartProductDefaultLoading(Request $request)
    {
        $saleCart = session()->has('saleCart') ? session()->get('saleCart')  :[];
        $view = view('backend.admin.purchase.createSaleAddToCart')->render();
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
                    'company_id' => 'required',
                    'project_id' => 'required',
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
                $purchase->type = 'purchase';
                $purchase->store_id = $request->store_id;
                $purchase->project_id = $request->project_id;
                $purchase->company_id = $request->company_id;
                $purchase->supplier_id = $request->supplier_id;
                $purchase->description = $request->description;
                $purchase->total_quantity = $request->totalProductQuantity;
                $purchase->total_price = $request->totalProductPrice;
                $purchase->cash_hand = $cash_hand;
                $purchase->user_id = Auth::user()->id;
                $purchase->created_at = $_POST['date'].' '.date('h:i:s');
                $save =  $purchase->save();
                $purchase_id = $purchase->id;
                $store_id =$purchase->store_id;
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
                    }
                }
                if($request->payment_amount &&  $request->payment_amount > 0)
                {
                    $payment  = new Purchase();
                    $payment->supplier_id = $request->supplier_id;
                    $payment->invoice_no = $invoice_no;
                    $payment->type = 'payment';
                    $payment->total_price = $request->payment_amount;
                    $payment->method_id = $request->payment_method_id;
                    $payment->user_id = Auth::user()->id;
                    $payment->cash_hand = $cash_hand - $request->payment_amount;
                    $payment->created_at = $_POST['date'].' '.date('h:i:s');
                    $payment->save();
                }
                DB::commit();
                if($save)
                {
                    session(['saleCart' => []]);
                    return redirect()->route('admin.purchase.index')->with('success','New Purchese is Created successfully!');
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!auth()->user()->can('purchase.index')){
            abort(403, 'Unauthorized action.');
        }
        
        $data['purchase'] = Purchase::with('purchaseDetails', 'projects', 'companies')->findOrFail($id);
        // dd($data);
        return view('backend.admin.purchase.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->can('purchase.index')){
            abort(403, 'Unauthorized action.');
        }

        $purchase=Purchase::with('purchaseDetails')->findOrFail($id);
        $project_company_id=$purchase->company_id;
        $supplier_type_id=$purchase->suppliers->type_id;

        $data['payment_methods'] = Payment_method::whereNull('deleted_at')->get();
        $data['products'] = Product::whereNull('deleted_at')->get();
        $data['suppliers'] = Supplier::where('type_id',$supplier_type_id)->whereNull('deleted_at')->get();
        $data['projects'] = Project::where('company_id',$project_company_id)->whereNull('deleted_at')->get();
        $data['purchase'] = $purchase;
        return view('backend.admin.purchase.edit',$data);
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
                    'project_id' => 'required',
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
                    $purchase->project_id = $request->project_id;
                    $purchase->company_id = $project->company_id;
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
                            }
                            $purchase_detail->save();
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

                    $purchase->project_id = $request->project_id;
                    $purchase->company_id = $project->company_id;
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
                            }
                            $purchase_detail->save();
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

                    $purchase->project_id = $request->project_id;
                    $purchase->company_id = $project->company_id;
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
                            }
                            $purchase_detail->save();
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
                    return redirect()->back()->with('success','Purchese is Update successfully!');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('purchase.index')){
            abort(403, 'Unauthorized action.');
        }

    DB::beginTransaction();
    try
        {
        $purchase=Purchase::find($id);


        $get_up_data = Purchase::where('supplier_id', $purchase->supplier_id)
                             ->where('id', '>', $id)
                             ->get(); 
                                     

           if ($purchase->type== 'purchase') {
             if (!empty($get_up_data)) {
                 foreach ($get_up_data as  $value) {
                    $value->cash_hand = $value->cash_hand - $purchase->total_price;
                    $value->save();
                 }
             }
         }elseif($purchase->type=='payment') {
             if (!empty($get_up_data)) {
                 foreach ($get_up_data as  $value) {
                    $value->cash_hand = $value->cash_hand + $purchase->total_price;
                    $value->save();
                 }
             }
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

   

   
}
