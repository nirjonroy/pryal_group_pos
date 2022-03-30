<?php

namespace App\Http\Controllers\Backend\Admin\Supplier;

use App\Http\Controllers\Controller;
use App\Model\Backend\Admin\Supplier\Supplier;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
use App\Model\Backend\Admin\Purchase\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use App\SupplierType;
use Carbon\Carbon;
use App\Utils\QuantityManage;
class SupplierController extends Controller
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
        if(!auth()->user()->can('supplier.index')){
            abort(403, 'Unauthorized action.');
        }

        $data['types']=SupplierType::orderBy('name','asc')->get();
        
        $query1=Supplier::orderby('name','asc');
                if(request()->type_id !=''){
                    $query1->where('type_id',request()->type_id);
                }
        $data['sups']=$query1->get();
                
        $query= Supplier::with('type')->whereNull('deleted_at');
                if(request()->type_id !=''){
                    $query->where('type_id',request()->type_id);
                }
                
                if(request()->supplier_id !=''){
                    $query->where('suppliers.id',request()->supplier_id);
                }
        $data['suppliers']=$query->latest()->get();
        return view('backend.admin.supplier.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('supplier.create')){
            abort(403, 'Unauthorized action.');
        }

        $types=SupplierType::orderBy('name','asc')->get();
        return view('backend.admin.supplier.create',compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('supplier.create')){
            abort(403, 'Unauthorized action.');
        }

        $input = $request->except('_token');
        $validator = Validator::make($input,[
                'name' => 'required|min:2|max:255',
                'contract_phone' => 'required|min:5|max:15|unique:suppliers,contract_phone',
                'address' => 'nullable',
                'note' => 'nullable',
                'type_id' => 'required|numeric',
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->contract_phone = $request->contract_phone;
        $supplier->address = $request->address;
        $supplier->note = $request->note;
        $supplier->type_id = $request->type_id;
        $supplier->created_by = Auth::user()->id;
        $save = $supplier->save();
        if($save)
        {
            return redirect()->route('admin.supplier.index')->with('success','New Supplier is created successfully!!');
        }
        else{
            return redirect()->back()->with('error','New Supplier is not created!!');
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
        if(!auth()->user()->can('supplier.view')){
            abort(403, 'Unauthorized action.');
        }

        $data['supplier']=Supplier::find($id);



        $query2=Purchase::where('supplier_id',$id)->with('companies','projects','method')->where('type', 'stock');
                 if(request()->start_date and request()->end_date !=''){
                    $query2->whereBetween('created_at',[request()->start_date.' 00:00:00',request()->end_date.' 23:59:00']);
                }
        $data['purchase']=$query2->orderby('id','asc')->get();



        return view('backend.admin.supplier.show',$data);
    }





     

     public function getByDay($id){
        
        $data['supplier']=Supplier::find($id);
        $all=Purchase::where('supplier_id',$id)->with('companies','projects','method')->get();
        
        // return response()->json( $all);
       
        $count = ($all->count());
        $data['purchase']=Purchase::where('supplier_id',$id)
                          ->where(
                                'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
                                )
                          ->with('companies','projects','method')->get();


         

        
        
       return view('backend.admin.supplier.supplier_statement_show_by_day', $data);
     }


  public function getByMonth($id){

    $data['supplier']=Supplier::find($id);
        $all=Purchase::where('supplier_id',$id)->with('companies','projects','method')->get();
        
        // return response()->json( $all);
       
        $count = ($all->count());
        $data['purchase']=Purchase::where('supplier_id',$id)
                          ->where(
                                'created_at', '>=', Carbon::now()->subDays(90)->toDateTimeString()
                                )
                          ->with('companies','projects','method')->get();

         
       return view('backend.admin.supplier.supplier_statement_show_three_month', $data);


  }


     // function updateSupplierPurchase($id){
           
     //      $all=Purchase::where('supplier_id',$id)->get();

     //      $cash_hand = 0;

     //      foreach ($all as $data) 
     //      {
             
     //           $cash_hand=$data->type=='purchase'? $cash_hand + $data->total_price:$cash_hand - $data->total_price;

     //        $data->cash_hand = $cash_hand;
     //         $data->save();
     //      }
     //      return redirect()->back();
     // }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->can('supplier.edit')){
            abort(403, 'Unauthorized action.');
        }

        $data['types']=SupplierType::all();
        $data['supplier'] = Supplier::findOrFail($id);
        return view('backend.admin.supplier.edit',$data);
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
        if(!auth()->user()->can('supplier.edit')){
            abort(403, 'Unauthorized action.');
        }

        $input = $request->except('_token');
        $validator = Validator::make($input,[
                'name' => 'required|min:2|max:255',
                'contract_phone' => 'required|min:5|max:15|unique:suppliers,contract_phone,'.$id,
                'type_id' => 'required|numeric',
                'address' => 'nullable',
                'note' => 'nullable',
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
        $supplier = Supplier::findOrFail($id);
        $supplier->name = $request->name;
        $supplier->contract_phone = $request->contract_phone;
        $supplier->address = $request->address;
        $supplier->note = $request->note;
        $supplier->type_id = $request->type_id;
        $supplier->created_by = Auth::user()->id;
        $save = $supplier->save();
        if($save)
        {
            return redirect()->route('admin.supplier.index')->with('success','Supplier is Updated successfully!!');
        }
        else{
            return redirect()->back()->with('error','Supplier is not Updated!!');
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
       
    }


    public function supplierPayment(){

        if(!auth()->user()->can('supplier_payment.create')){
            abort(403, 'Unauthorized action.');
        }


        $types=SupplierType::orderBy('name','asc')->get();
        $query=Supplier::with('purchase_payment','purchase');

        if (!empty(request()->search)) {
            $query->where('name','like', '%'.request()->search.'%');
        }

        if (request()->type !='') {
            $query->where('type',request()->type);
        }

        $suppliers=$query->orderBy('name','asc')->get();
        return view('backend.admin.supplier.supplier_payment',compact('suppliers','types'));

    }

    public function getPaymentModal($id){
        $methods=DB::table('payment_methods')->get();
        $supplier=Supplier::with('purchase_payment','purchase','stock_purchase', 'stockPurchase', 'purchaseStockpayment')->find($id);
        return view('backend.admin.supplier.payment_modal',compact('supplier','methods'));
    }

    public function purchasePayment(Request $request){
        $supplier=request('supplier_id');
        $date=request('date').' '.date('h:i:s');
        $purchases=Purchase::with('payments')
                ->where('supplier_id',$supplier)
                ->where('type','stock')
                ->where('status','!=','paid')
                ->orderBy('id','asc')
                ->get();
    
        $amount=request('payment_amount');
        
        

        foreach($purchases as $purchase){
            
            $due=$purchase->total_price-$purchase->payments->sum('total_price');
            
            $can_paid=0;
            if($due <= $amount){
                $can_paid=$due;
            }else if($due >$amount){
                $can_paid=$amount;
            }
            
            $payment  = new Purchase();
            $payment->transction_id = $purchase->id;
            $payment->supplier_id = $request->supplier_id;
            $payment->invoice_no = 'pay';
            $payment->type = 'payment';
            $payment->description = request('note');
            $payment->total_price = $can_paid;
            $payment->method_id =request('payment_method_id');
            $payment->user_id = Auth::user()->id;
            $payment->created_at = $date;
            $payment->save();
            
            $this->quantityManage->purchaseStatusUpdate($purchase->id);
            $amount =$amount - $can_paid;
            
            if($amount==0){
                break;
            }
            
            
        }
        
        
        

        

        return back()->with('success','supplier Payment Is successful!!');
    }
    
    public function supplier_payment(){
        
    }
    
}
