<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Model\Backend\Admin\Purchase\Purchase;
use App\Model\Backend\Admin\Project\Project_payment_history;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Expense\Expense;
use DB;
Use App\BankHistory;
Use App\Sell;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data=[];
        $data['project']=Project::all();
        $data['purchase']=Purchase::where('type','purchase')->sum('total_price');
        $data['stock_purchase']=Purchase::where('type','stock')->sum('total_price');

        $data['project_payment']=Project_payment_history::sum('payment_amount');
        $data['purchase_payment']=Purchase::whereIn('type',['payment','stock_payment'])->sum('total_price');
        $data['expense']=Expense::sum('total_price');
        $data['transfer']=Sell::where('type','sell')->sum('total_price');
        $data['return']=Sell::where('type','stock_return')->sum('total_price');
        $data['running_project']=Project::where('working_status',0)->get();
        $data['complete_project']=Project::where('working_status',1)->get();
        $data['work_project']=Project::where('working_status',2)->get();
        $data['partner_project']=Project::where('working_status',3)->get();
        $data['bank_in']=BankHistory::where('type','in')->sum('amount');
        $data['bank_out']=BankHistory::where('type','out')->sum('amount');
        $data['today_total_purchase']=Purchase::whereDate('created_at', date('Y-m-d'))->whereIn('type',['purchase','stock'])->sum('total_price');
        $data['today_total_payment']=Purchase::whereDate('created_at', date('Y-m-d'))->whereIn('type',['payment'])->sum('total_price');
        $data['total_quantity']=DB::table('quantity_stores')->sum('quantity_available');
        $data['total_quantity_amount']=DB::table('quantity_stores as qs')
                                            ->join('products as p','p.id','qs.product_id')
                                            ->select(DB::raw("SUM(qs.quantity_available * p.unit_price) as total_amount"))
                                            ->get()->sum('total_amount');

        $purchase_total = DB::table('purchases')
                        ->where('type', 'stock')
                        ->sum('total_price');

        $purchase_qty = DB::table('purchases')
                        ->where('type', 'stock')
                        ->sum('total_quantity');
                        
        $sell_qty = DB::table('sells')                
                    ->where('type', 'sell')
                    ->sum('total_quantity');

        $sell_price = DB::table('sells')                
                    ->where('type', 'sell')
                    ->sum('total_price');            
                    // dd($purchase);
        $expense   =DB::table('expenses')
                    ->sum('total_price');            

        return view('layouts.backend.partial.new_blank_page',compact('data', 'purchase_total', 'purchase_qty', 'sell_qty', 'sell_price', 'expense'));
    }
}
