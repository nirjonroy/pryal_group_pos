<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\CustomerType;
use App\Sell;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Utils\QuantityManage;

class CustomerController extends Controller
{
    protected $quantityManage;
    public function __construct(QuantityManage $quantity_manage)
    {
        $this->quantityManage=$quantity_manage;
        
    }
    
    public function index()
    {
        $data['types']=CustomerType::orderBy('name','asc')->get();
        
        $query1=Customer::orderby('name','asc');
                if(request()->type_id !=''){
                    $query1->where('type_id',request()->type_id);
                }
        $data['cus']=$query1->get();
                
        $query= Customer::with('type', 'sell', 'sell_payment')->whereNull('deleted_at');
                if(request()->type_id !=''){
                    $query->where('type_id',request()->type_id);
                }
                
                if(request()->customer_id !=''){
                    $query->where('customers.id',request()->customer_id);
                }
        $data['customer']=$query->latest()->get();

         return view('backend.admin.customer.index', $data);
       
    }
    public function create()
    {
        $types=CustomerType::orderBy('name','asc')->get();
        return view('backend.admin.customer.create', compact('types'));
    }
    public function store(Request $request)
    {
         
         $data= $request->validate([
            'name' => 'required|min:2|max:255',
                'contract_phone' => 'required|min:5|max:15|unique:customers,contract_phone',
                'address' => 'nullable',
                'note' => 'nullable',
                'type_id' => 'required|numeric',
            
        ]);
        
        Customer::insert($data);
        return redirect('customer')->with('success', 'Created successfully');
    }
    public function edit($id)
    {
        $data['types']=CustomerType::all();
        $data['customer'] = Customer::findOrFail($id);
       return view('backend.admin.customer.edit', $data);
    }
    public function update(Request $request, $id)
    {
        $customer=Customer::find($id);
        $customer->name=request('name');
        $customer->contract_phone=request('contract_phone');
        $customer->address=request('address');
        $customer->type_id = $request->type_id;
        $customer->note=request('note');
        $customer->save();
        return back()->with('success', 'Update Success');
    }

    public function destroy_customer(Request $request, $id)
    {
        $data=Customer::findOrFail($id);
        $data->delete();
        return back()->with('success', 'Delete Success');
    }

        public function customerPayment(){
        
        $types=CustomerType::orderBy('name','asc')->get();
        $query=Customer::with('Sell_payment','sell');

        if (!empty(request()->search)) {
            $query->where('name','like', '%'.request()->search.'%');
        }

        if (request()->type !='') {
            $query->where('type',request()->type);
        }

        $customers=$query->orderBy('name','asc')->get();
        return view('backend.admin.customer.customer_payment',compact('customers','types'));

    }
    public function getPaymentModal($id){
        $methods=DB::table('payment_methods')->get();
        $customer=Customer::with('Sell_payment','sell')->find($id);
        
        
        
        // dd($customer);
        return view('backend.admin.customer.payment_modal',compact('customer','methods'));
        
    }

     public function purchasePayment(Request $request){
        $customer_id=request('customer_id');
        $date=request('date').' '.date('h:i:s');
        $sells=Sell::with('payments')
                ->where('customer_id',$customer_id)
                ->where('type','sell')
                ->where('status','!=','paid')
                ->orderBy('id','asc')
                ->get();
    
        $amount=request('payment_amount');
        
        

        foreach($sells as $sell){
            
            $due=$sell->total_price-$sell->payments->sum('total_price');
            
            $can_paid=0;
            if($due <= $amount){
                $can_paid=$due;
            }else if($due >$amount){
                $can_paid=$amount;
            }
            
            $payment  = new Sell();
            $payment->transction_id = $sell->id;
            $payment->customer_id = $customer_id;
            $payment->invoice_no = 'pay';
            $payment->type = 'payment';
            $payment->description = request('note');
            $payment->total_price = $can_paid;
            $payment->method_id =request('payment_method_id');
            $payment->user_id = Auth::user()->id;
            $payment->created_at = $date;
            $payment->save();
            
            $this->quantityManage->sellStatusUpdate($sell->id);
            $amount =$amount - $can_paid;
            
            if($amount==0){
                break;
            }
            
            
        }
        
        
        

        

        return back()->with('success','Customer Payment Is successful!!');
    }
    
    public function view(Request $request, $id){
        $data['customer']=Customer::find($id);



        $query2=Sell::where('customer_id',$id)->with('companies','projects','method', 'payments')->where('type', 'sell');
                 if(request()->start_date and request()->end_date !=''){
                    $query2->whereBetween('created_at',[request()->start_date.' 00:00:00',request()->end_date.' 23:59:00']);
                }
        $data['sell']=$query2->orderby('id','asc')->get();
        // dd($data);
        return view('backend.admin.customer.view', $data);
    }
    
}
