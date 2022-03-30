<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Project\Project_payment_history;
use App\Model\Backend\Admin\Expense\Expense;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
use App\Model\Backend\Admin\Purchase\Purchase_details;
use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Product\Product;
use App\Model\Backend\Admin\Supplier\Supplier;
use DB;
use Auth;
use App\ProjectType;
use App\SupplierType;
use App\Sell;
use App\Customer;
use App\CustomerType;
use Carbon\Carbon;
class ReportController extends Controller
{
    

    public function ProjectWise(){

         if(!auth()->user()->can('project_wise_report.index')){
            abort(403, 'Unauthorized action.');
        }

            $com=Company::orderby('name','asc');
                    if(request()->company_type_id !=''){
                    $com->where('type_id',request()->company_type_id);
            }
            $companies=$com->get();
            $types=ProjectType::orderby('name','asc')->get();
            $query_ps=Project::orderby('name','asc')->with('user');

            if(request()->status !=''){
                $query_ps->where('working_status',request()->status);
            }
            
            if(request()->company_id !=''){
                $query_ps->where('company_id','like',request()->company_id);
            }

            $ps=$query_ps->get();
            
    		$query=Project::with('user','companies','projectPayment','purchase','type','expense','sell','sell_return');
    		
    		if(!empty($_GET['search'])){
    			$query->where('projects.name','like', '%'.$_GET['search'].'%');
    		}

            if(request()->company_id!=''){
                $query->where('projects.company_id',request()->company_id);
            }

            if(request()->status !=''){
                $query->where('projects.working_status',request()->status);
               
            }

            if(request()->type_id !=''){
                $query->where('projects.project_type_id',request()->type_id);
            }

            if(request()->project_id !=''){
                $query->where('projects.id',request()->project_id);
            }

             if(Auth::user()->hasRole('Partners')){
              $query=$query->where('project_partner', Auth::user()->id);
          }

          $project_data = clone $query;

          if(request()->alldata){
            $projects= $query->paginate(1000);
          }else{
            $projects= $query->paginate(20);

          }


          $project_count = ($projects->perPage() * $projects->currentPage());

          $this_page_projects=$project_data->limit($project_count)->get();

          $total_query=Project::with('user','companies','projectPayment','purchase','type','expense','sell','sell_return');
                        if(request()->company_id!=''){
                            $total_query->where('projects.company_id',request()->company_id);
                        }
            
                        if(request()->status !=''){
                            $total_query->where('projects.working_status',request()->status);
                           
                        }
            
                        if(request()->type_id !=''){
                            $total_query->where('projects.project_type_id',request()->type_id);
                        }
            
                        if(request()->project_id !=''){
                            $total_query->where('projects.id',request()->project_id);
                        }
            
                        if(Auth::user()->hasRole('Partners')){
                            $total_query->where('project_partner', Auth::user()->id);
                        }
          $project_total=$total_query->get();
          
          $status_total = Project::with('user','companies','projectPayment','purchase','type','expense','sell_return','sell')->where('projects.working_status',request()->status)->get();

    	return view('backend.admin.report.project_wise',compact('projects','companies','ps','types', 'project_total', 'status_total', 'this_page_projects'));
    }

    public function projectDetails($id){
        
        $project=Project::with('user','companies','type')->find($id);
        
        
        $exp=Expense::where('project_id',$id);
        $pay=Project_payment_history::where('project_id',$id);
        $pur=Purchase::with('suppliers')->where('project_id',$id)->where('type','purchase');
        $pur=Purchase::with('suppliers')->where('project_id',$id)->where('type','purchase');
        $sell=Sell::where('project_id',$id)->where('type','sell');
        $sell_return=Sell::where('project_id',$id)->where('type','stock_return');
        
        if(request('start') !='' and request('end') !=''){
            
            $exp->whereBetween('expense_date',[request('start'), request('end')]);
            $pur->whereBetween('created_at',[request('start').' 00:00:00', request('end').' 23:59:00']);
            $pay->whereBetween('created_at',[request('start').' 00:00:00', request('end').' 23:59:00']);
            $sell->whereBetween('created_at',[request('start').' 00:00:00', request('end').' 23:59:00']);
            $sell_return->whereBetween('created_at',[request('start').' 00:00:00', request('end').' 23:59:00']);
            
        }
        
        $expense=$exp->get();
        $sells=$sell->get();
        $sell_returns=$sell_return->get();
        $payments=$pay->get();
        $purchase=$pur->get();
        
        return view('backend.admin.report.project_details',compact('project','expense','payments','purchase','id','sells','sell_returns'));
    }
// Supplier Wise start here

    public function supplierWise(){
         if(!auth()->user()->can('supplier_wise_report.index')){
            abort(403, 'Unauthorized action.');
        }

        $types=SupplierType::all();

        $sup=Supplier::orderby('name','asc');
        if (request()->type_id !='') {
            $sup->where('type_id',request()->type_id);
        }

        $sups=$sup->get();


        $query=Supplier::orderby('name','asc')->with('purchaseStockpayment','stockPurchase');

        if (!empty(request()->supplier_id)) {
            $query->where('id',request()->supplier_id);
        }

        if (request()->type_id !='') {
            $query->where('type_id',request()->type_id);
        }

        $supplier_data = clone $query;
    
        if(request()->alldata){
            $suppliers=$query->paginate(1000);
        }else{
            $suppliers=$query->paginate(20);

        }

        $project_count = ($suppliers->perPage() * $suppliers->currentPage());

        $this_page_suppliers=$supplier_data->limit($project_count)->get();
        
        
        $total_query = Supplier::orderby('name','asc')->with('purchaseStockpayment','stockPurchase');
                        if (!empty(request()->supplier_id)) {
                            $total_query->where('id',request()->supplier_id);
                        }
                
                        if (request()->type_id !='') {
                            $total_query->where('type_id',request()->type_id);
                        }
        $total_suppliers=$total_query->get();
        
        //dd($total_suppliers);
    	return view('backend.admin.report.supplier_wise',compact('suppliers','types','sups', 'total_suppliers', 'this_page_suppliers'));
    }


    public function customerWise(){
         
        $types=CustomerType::all();

        $cus=Customer::orderby('name','asc');
        if (request()->type_id !='') {
            $cus->where('type_id',request()->type_id);
        }

        $cuss=$cus->get();


        $query=Customer::orderby('name','asc')->with('sell_payment', 'sell');

        if (!empty(request()->customer_id)) {
            $query->where('id',request()->customer_id);
        }

        if (request()->type_id !='') {
            $query->where('type_id',request()->type_id);
        }

        $customer_data = clone $query;
    
        if(request()->alldata){
            $customers=$query->paginate(1000);
        }else{
            $customers=$query->paginate(20);

        }

        $project_count = ($customers->perPage() * $customers->currentPage());

        $this_page_customers=$customer_data->limit($project_count)->get();
        
        
        $total_query = Customer::orderby('name','asc')->with('stock_return');
                        if (!empty(request()->customer_id)) {
                            $total_query->where('id',request()->customer_id);
                        }
                
                        if (request()->type_id !='') {
                            $total_query->where('type_id',request()->type_id);
                        }
        $total_customers=$total_query->get();
        
        //dd($total_suppliers);
        // dd($customers);
        return view('backend.admin.report.customer_wise',compact('customers','types','cuss', 'total_customers', 'this_page_customers'));
    }


   public function supplierWiseView($id){
    if(!auth()->user()->can('supplier_wise_report.index')){
            abort(403, 'Unauthorized action.');
        }

        $data['supplier']=Supplier::find($id);



        $query2=Purchase::where('supplier_id',$id)->with('companies','projects','method');
                 if(request()->start_date and request()->end_date !=''){
                    $query2->whereBetween('created_at',[request()->start_date.' 00:00:00',request()->end_date.' 23:59:00']);
                }
        $data['purchase']=$query2->orderby('created_at','asc')->get();
        return view('backend.admin.report.supplier_wise_report_view',$data);
   }

    public function customerWiseView($id){
    

        $data['customer']=Customer::find($id);



        $query2= Sell::where('customer_id',$id)->with('method');
                 if(request()->start_date and request()->end_date !=''){
                    $query2->whereBetween('created_at',[request()->start_date.' 00:00:00',request()->end_date.' 23:59:00']);
                }
        $data['sell']=$query2->orderby('created_at','asc')->get();
        return view('backend.admin.report.customer_wise_report_view',$data);
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


         

        
        
       return view('backend.admin.report.supplier_wise_report_day', $data);
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

         
       return view('backend.admin.report.supplier_wise_report_month', $data);


  }

  // Supplier Wise end here



    public function purchaseWise(){
         if(!auth()->user()->can('purchase_wise_report.index')){
            abort(403, 'Unauthorized action.');
        }


        $pro =Project::orderby('name','asc');
                if(request()->status !=''){
                    $pro->where('working_status',request()->status);
                }

                if(request()->company_id!=''){
                    $pro->where('company_id',request()->company_id);
                }
        $data['projects']=$pro->get();
        $data['sups'] =Supplier::orderby('name','asc')->get();
        $com=Company::orderby('name','asc');
            if(request()->type_id !=''){
                    $com->where('type_id',request()->type_id);
                }
        $data['coms']=$com->get();

    	$query=Purchase::with('suppliers','projects','projects.companies')->where('type','purchase');

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

        $purchases_data = clone $query;

         if(request()->alldata){
           $data['purchases']=$query->latest()->paginate(2000);
          }elseif(request()->date_start and request()->date_end !=''){
              $data['purchases']=$query->latest()->paginate(2000);
          }else{
           $data['purchases']=$query->latest()->paginate(30);

          }

        $project_count = ($data['purchases']->perPage() * $data['purchases']->currentPage());

        $data['this_page_purchases']=$purchases_data->limit($project_count)->get();

        $total_query = Purchase::with('suppliers','projects','projects.companies')->where('type','purchase');
                        if(request()->project_id !=''){
                            $total_query->where('project_id',request()->project_id);
                        }
                
                        if(request()->company_id!=''){
                            $total_query->where('company_id',request()->company_id);
                        }
                
                        if(request()->supplier_id !=''){
                            $total_query->where('supplier_id',request()->supplier_id );
                        }
        $data['purchases_totals']=$total_query->get();

        
    	return view('backend.admin.report.purchase_wise',$data);
    }


    public function purchaseWiseProduct($id){
    	$purchase=Purchase::with('suppliers','projects','purchaseDetails','purchaseDetails.products')->find($id);
    	return view('backend.admin.report.product_modal',compact('purchase'));
    }



  public function purchaseWiseReportshow($id)
    {
        if(!auth()->user()->can('purchase.index')){
            abort(403, 'Unauthorized action.');
        }
        
        $data['purchase'] = Purchase::with('purchaseDetails')->findOrFail($id);
        return view('backend.admin.purchase.show',$data);
    }



    // Purchase wise start here

    public function companyWise(){
         if(!auth()->user()->can('company_running_project_report.index')){
            abort(403, 'Unauthorized action.');
        }

        $com=Company::orderby('name','asc');
            if(request()->type_id !=''){
    			    $com->where('type_id',request()->type_id);
    			}
        $coms=$com->get();
        
    	$query=Company::with('projects','payments','expense','purchase');
    			if(!empty($_GET) and ($_GET['id'] !='')) {
    				$query->where('companies.id',$_GET['id']);
    			}
    			
    			if(request()->type_id !=''){
    			    $query->where('type_id',request()->type_id);
    			}

    	$companies =$query->get();
    	return view('backend.admin.report.company_wise',compact('companies','coms'));
    }

    
    
    public function companyWiseComplete(){
        if(!auth()->user()->can('company_complete_project_report.index')){
            abort(403, 'Unauthorized action.');
        }
         if(!auth()->user()->can('report.index')){
            abort(403, 'Unauthorized action.');
        }

        $com=Company::orderby('name','asc');
            if(request()->type_id !=''){
    			    $com->where('type_id',request()->type_id);
    			}
        $coms=$com->get();
        $query=Company::with('projects_c','payments_c','expense_c','purchase_c');
                if(request()->id !='') {
                    $query->where('companies.id',request()->id);
                }
                
                if(request()->type_id !=''){
    			    $query->where('type_id',request()->type_id);
    			}
        $companies=$query->get();
        return view('backend.admin.report.company_wise_complete',compact('companies','coms'));
    }

    public function productWise(){
         if(!auth()->user()->can('product_wise_report.index')){
            abort(403, 'Unauthorized action.');
        }


        $products=Product::orderby('name','asc')->get();
        $query=Purchase_details::join('products','products.id','=','purchase_details.product_id')
                ->join('sells_details', 'sells_details.id', 'products.id')
                ->join('units','products.unit_id','=','units.id')
                ->groupby('products.id','products.name','units.name')
                ->select('products.name','units.name as u_name',
                    DB::raw('count(products.id) as product_count'),
                    DB::raw('SUM(purchase_details.quantity) as total_quantity'),
                    DB::raw('SUM(purchase_details.total_price) as total_price'),
                    DB::raw('SUM(sells_details.quantity) as sells_quantity'),
                    DB::raw('SUM(sells_details.total_price) as sells_total')
                );
        if(request()->date_start and request()->date_end !=''){
            $query->whereBetween('purchase_details.created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
        }

        if(request()->product_id !=''){
            $query->where('purchase_details.product_id',request()->product_id);
        }

        $products_data = clone $query;

        if(request()->alldata){
          $purchase_details=$query->paginate(2000);
          }elseif(request()->date_start and request()->date_end !=''){
            $purchase_details=$query->paginate(2000);
          }else{
           $purchase_details=$query->paginate(30);

          }


          $project_count = ($purchase_details->perPage() * $purchase_details->currentPage());

        $this_page_valus=$products_data->limit($project_count)->get();


           $total_valus=Purchase_details::join('products','products.id','=','purchase_details.product_id')
                ->join('sells_details', 'sells_details.id', 'products.id')
                ->join('units','products.unit_id','=','units.id')
                ->groupby('products.id','products.name','units.name')
                ->select('products.name','units.name as u_name',
                    DB::raw('count(products.id) as product_count'),
                    DB::raw('SUM(purchase_details.quantity) as total_quantity'),
                    DB::raw('SUM(sells_details.quantity) as sell_quantity'),
                    DB::raw('SUM(purchase_details.total_price) as total_price'),
                    DB::raw('SUM(sells_details.total_price) as sell_price')
                )->get();

    
        $purchase_details=$query->paginate(300000);
                //  dd($purchase_details);
                // dd($total_valus);
                
            
    	return view('backend.admin.report.product_wise',compact('purchase_details','products','total_valus', 'this_page_valus'));


    }

    public function dailyStatement(){
         
        $purchase=Purchase::where('type','stock')->orderby('created_at', 'DESC');

        if(request()->date_start and request()->date_end !=''){
            $purchase->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00'])->orderby('created_at', 'DESC');
        }else{
            $purchase->whereDate('created_at',date('Y-m-d'));
        }

        $exp=Expense::orderby('expense_date', 'DESC');

        if(request()->date_start and request()->date_end !=''){
            $exp->whereBetween('expense_date',[request()->date_start,request()->date_end]);
        }else{
            $exp->whereDate('expense_date',date('Y-m-d'));
        }


        $sell=Sell::where('type','sell')->orderby('created_at', 'DESC');

        if(request()->date_start and request()->date_end !=''){
            $sell->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00'])->orderby('created_at', 'DESC');
        }else{
            $sell->whereDate('created_at',date('Y-m-d'));
        } 


        $recei=Project_payment_history::with('project','method')->orderby('created_at', 'DESC');

        if(request()->date_start and request()->date_end !=''){
            $recei->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
        }else{
            $recei->whereDate('created_at',date('Y-m-d'));
        }

        //  supplier payment

        $pay=Purchase::with('method','suppliers')->whereIn('type',['stock_payment','payment']);
                if(request()->date_start and request()->date_end !=''){
                    $pay->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                }else{
                    $pay->whereDate('created_at',date('Y-m-d'));
                }
        $cus=Sell::with('method','customer')->whereIn('type',['sell','payment']);
                if(request()->date_start and request()->date_end !=''){
                    $pay->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                }else{
                    $pay->whereDate('created_at',date('Y-m-d'));
                }        
        $payments=$pay->latest()->get();
        $customer = $cus->latest()->get();
        $received=$recei->get();
        $expenses=$exp->get();
       
        $purchases=$purchase->get();
        $sells = $sell->get();
        return view('backend.admin.report.daily_statement',compact('purchases','expenses','received','payments', 'sells', 'customer'));
    }

    public function dailyStatementLastThreeDay(){
           $purchases=Purchase::with('projects')->where('type','purchase')
                            ->where('created_at', '>=', Carbon::now()->subDays(3)->toDateTimeString())
                            ->get();

            $expenses=Expense::with('projects')->where(
                                'created_at', '>=', Carbon::now()->subDays(3)->toDateTimeString()
                                )->orderby('expense_date', 'DESC')->get();

            $received=Project_payment_history::with('project','method')->where(
                                'created_at', '>=', Carbon::now()->subDays(3)->toDateTimeString()
                                )->get();
            $payments=Purchase::with('method','suppliers')->whereIn('type',['stock_payment','payment'])
                        ->where('created_at', '>=', Carbon::now()->subDays(3)->toDateTimeString())
                        ->get();
            return view('backend.admin.report.daily_statement',compact('purchases','expenses','received','payments'));

    }

   

     public function dailyStatementLastFiveDay(){
           $purchases=Purchase::with('projects')->where('type','purchase')
                            ->where('created_at', '>=', Carbon::now()->subDays(5)
                                ->toDateTimeString()
                                )->get();

            $expenses=Expense::with('projects')->where(
                                'created_at', '>=', Carbon::now()->subDays(5)->toDateTimeString()
                                )->orderby('expense_date', 'DESC')->get();

            $received=Project_payment_history::with('project','method')->where(
                                'created_at', '>=', Carbon::now()->subDays(5)->toDateTimeString()
                                )->get();
            $payments=Purchase::with('method','suppliers')->whereIn('type',['stock_payment','payment'])
                            ->where('created_at', '>=', Carbon::now()->subDays(5)->toDateTimeString())
                            ->get();
                                
            return view('backend.admin.report.daily_statement',compact('purchases','expenses','received','payments'));

    }

    

     public function dailyStatementLastTenDay(){
           $purchases=Purchase::with('projects')->where('type','purchase')
                                ->where(
                                'created_at', '>=', Carbon::now()->subDays(10)->toDateTimeString()
                                )->get();

            $expenses=Expense::with('projects')->where(
                                'created_at', '>=', Carbon::now()->subDays(10)->toDateTimeString()
                                )->get();

            $received=Project_payment_history::with('project','method')->where(
                                'created_at', '>=', Carbon::now()->subDays(10)->toDateTimeString()
                                )->get();
            $payments=Purchase::with('method','suppliers')->whereIn('type',['stock_payment','payment'])
                                ->where(
                                'created_at', '>=', Carbon::now()->subDays(10)->toDateTimeString()
                                )->get();
            return view('backend.admin.report.daily_statement',compact('purchases','expenses','received','payments'));

    }



    public function companyRunningDetails($id){

        $pro=Project::orderby('name','asc')->where('company_id',$id);
            if(request()->status !=''){
                $pro->where('working_status',request()->status);
            }
        $project=$pro->get();
        $company=Company::find($id);

        $query=Project::with('projectPayment')->where('company_id',$id);

        if(request()->status !=''){
            $query->where('working_status',request()->status);
        }

        if(request()->project_id !=''){
            $query->where('id',request()->project_id);
        }

        $projects=$query->get();
        return view('backend.admin.report.company_details',compact('company','projects','project'));
    }

     public function companyCompleteDetails($id){

        $pro=Project::orderby('name','asc')->where('company_id',$id);
            if(request()->status !=''){
                $pro->where('working_status',request()->status);
            }
        $project=$pro->get();
        $company=Company::find($id);

        $query=Project::with('projectPayment')->where('company_id',$id);

        if(request()->status !=''){
            $query->where('working_status',request()->status);
        }

        if(request()->project_id !=''){
            $query->where('id',request()->project_id);
        }

        $projects=$query->get();
        return view('backend.admin.report.company_details',compact('company','projects','project'));
    }

     public function companyWorkdoneDetails($id){

        $pro=Project::orderby('name','asc')->where('company_id',$id);
            if(request()->status !=''){
                $pro->where('working_status',request()->status);
            }
        $project=$pro->get();
        $company=Company::find($id);

        $query=Project::with('projectPayment')->where('company_id',$id);

        if(request()->status !=''){
            $query->where('working_status',request()->status);
        }

        if(request()->project_id !=''){
            $query->where('id',request()->project_id);
        }

        $projects=$query->get();
        return view('backend.admin.report.company_details',compact('company','projects','project'));
    }


     public function companyDetails($id){

        $pro=Project::orderby('name','asc')->where('company_id',$id);
            if(request()->status !=''){
                $pro->where('working_status',request()->status);
            }
        $project=$pro->get();
        $company=Company::find($id);

        $query=Project::with('projectPayment')->where('company_id',$id);

        if(request()->status !=''){
            $query->where('working_status',request()->status);
        }

        if(request()->project_id !=''){
            $query->where('id',request()->project_id);
        }

        $projects=$query->get();
        return view('backend.admin.report.company_details',compact('company','projects','project'));
    }


    // new report company wise 

    public function companyWiseWork(){
         if(!auth()->user()->can('company_work_done_report.index')){
            abort(403, 'Unauthorized action.');
        }

        $com=Company::orderby('name','asc');
            if(request()->type_id !=''){
                    $com->where('type_id',request()->type_id);
                }
        $coms=$com->get();
        $query=Company::with('projects_w','payments_w','expense_w','purchase_w');
                if(request()->id !='') {
                    $query->where('companies.id',request()->id);
                }
                
                if(request()->type_id !=''){
                    $query->where('type_id',request()->type_id);
                }
        $companies=$query->get();
        return view('backend.admin.report.company_wise_work',compact('companies','coms'));
    }


    public function companyWisePartner(){
         if(!auth()->user()->can('company_partner_investment_report.index')){
            abort(403, 'Unauthorized action.');
        }

        $com=Company::orderby('name','asc');
            if(request()->type_id !=''){
                    $com->where('type_id',request()->type_id);
                }
        $coms=$com->get();
        $query=Company::with('projects_p','payments_p','expense_p','purchase_p');
                if(request()->id !='') {
                    $query->where('companies.id',request()->id);
                }
                
                if(request()->type_id !=''){
                    $query->where('type_id',request()->type_id);
                }
        $companies=$query->get();
        return view('backend.admin.report.company_wise_partner',compact('companies','coms'));
    }

    public function yearlyProject(){

        // DB::table('projects')->where(['date'=>Null,'working_status'=>'1'])->update(['date'=>'2022-10-10']);
         if(!auth()->user()->can('yearly_complete_project_report.index')){
            abort(403, 'Unauthorized action.');
        }


        $data= Project::selectRaw('year(date) year, count(id) data, sum(project_value) amount,
                sum(working_status = 0) AS `running`, sum(working_status = 1) AS `complete` ,sum(working_status = 2) AS `work`,
                sum(working_status = 3) AS `partner`')
                ->groupBy('year')
                ->where('working_status',1)
                ->orderBy('year', 'desc')
                ->get();
        return view('backend.admin.report.yearly_project', compact('data'));
    }


    public function yearWiseProject($year){

        $projects= Project::with('user','companies','projectPayment','purchase','type','expense')
                ->whereYear('date',$year)
                ->where('working_status',1)
                ->paginate(30);
        return view('backend.admin.report.year_wise_project', compact('projects'));
    }






}
