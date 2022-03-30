<?php

namespace App\Http\Controllers\Backend\Admin\Expense;

use App\Http\Controllers\Controller;
use App\Model\Backend\Admin\Supplier\Supplier;
use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Expense\Expense;
use App\Model\Backend\Admin\Expense\Expense_detail;
use App\Model\Backend\Admin\Project\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\ExpenseType;
use App\CompanyType;
use App\ExpenseCategory;
class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('expense.index')){
            abort(403, 'Unauthorized action.');
        }

        $data['exp_type']='';
        $pro =Project::orderby('name','asc');
                if(request()->status !=''){
                    $pro->where('working_status',request()->status);
                }

                if(request()->company_id!=''){
                    $pro->where('company_id',request()->company_id);
                }
        $data['types']=CompanyType::orderBy('name','asc')->get();
        $data['projects']=$pro->get();
        $com =Company::orderby('name','asc');
               if(request()->type_id!=''){
                    $com->where('type_id',request()->type_id);
                } 
        $data['coms']=$com->get();
        
        $data['expense_types']=ExpenseType::all();
        $data['cats']=ExpenseCategory::all();
        $query = Expense::with('companies','projects','projects.type','category','expenseDetails','expenseDetails.type')
                ->whereNull('expenses.deleted_at')
                ->join('expense_details as ED','ED.expense_id','=','expenses.id')
                ->select('expenses.id','expenses.company_id','expenses.total_price','expenses.expense_date','expenses.category_id','expenses.description','expenses.project_id');
                if(request()->date_start and request()->date_end !=''){
                    $query->whereBetween('expenses.expense_date',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                }

                if(request()->project_id!=''){
                    $query->where('expenses.project_id',request()->project_id);
                }

                if(request()->category_id!=''){
                    $query->where('expenses.category_id',request()->category_id);
                }

                if(request()->company_id!=''){
                    $query->where('expenses.company_id',request()->company_id);
                }

                if(request()->expense_type_id!=''){
                    $query->where('ED.type_id',request()->expense_type_id);
                    $detail=ExpenseType::find(request()->expense_type_id);
                    $data['exp_type'].=$detail->name;
                }

                if (request()->shorting !='') {
                    $query->orderby('expenses.expense_date',request()->shorting);
                }

                $query->groupby('expenses.id','expenses.company_id','expenses.total_price','expenses.expense_date','expenses.category_id','expenses.description','expenses.project_id');
          

         if(request()->alldata){
           $data['expenses']=$query->orderBy('expense_date','desc')->paginate(5000);
          }elseif(request()->date_start and request()->date_end !=''){
              $data['expenses']=$query->orderBy('expense_date','desc')->paginate(5000);
          }else{
           $data['expenses']=$query->orderBy('expense_date','desc')->paginate(30);

          }
       

        $query_2 = Expense::whereNull('expenses.deleted_at');
         
            if(request()->date_start and request()->date_end !=''){
                    $query_2->whereBetween('expenses.expense_date',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
            }
            
            if(request()->project_id!=''){
                    $query_2->where('expenses.project_id',request()->project_id);
            }

            if(request()->category_id!=''){
                $query_2->where('expenses.category_id',request()->category_id);
            }

            if(request()->company_id!=''){
                $query_2->where('expenses.company_id',request()->company_id);
            }



        $data['total_summery']=$query_2->sum('total_price');
        return view('backend.admin.expense.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if(!auth()->user()->can('expense.create')){
            abort(403, 'Unauthorized action.');
        }

        $data['cats']=ExpenseCategory::all();
        $data['types']=ExpenseType::all();
        $data['coms'] = Company::whereNull('deleted_at')->latest()->get();
        $data['suppliers'] = Supplier::whereNull('deleted_at')->latest()->get();
        return view('backend.admin.expense.create',$data);
    }


    public function getProjectByCompanyId(Request $request)
    {
        if($request->company_id)
        {
            $projects =  Project::where('company_id',$request->company_id)->whereNull('deleted_at')
                ->where('working_status','!=', 1)->get();

            $html = '<option disabled selected>Select One</option>';
            foreach($projects as $project)
            {
                $html .= "<option  value='". $project->id ."'>" . $project->name . "</option>";
            }
            return $html;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if(!auth()->user()->can('expense.create')){
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try
        {
            $input = $request->except('_token');
            $validator = Validator::make($input,[
                    'category_id' => 'required',
                    
                    'type_id.*' => 'required',
                    'final_total.*' => 'required',
                    'totalExpensePrice' => 'required',
                ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $invoice_no = mt_rand(100000000, 999999999);

            $expense = new Expense();
            $expense->invoice_no = $invoice_no;
            $expense->category_id = $request->category_id;
            $expense->company_id = $request->company_id;
            $expense->project_id = $request->project_id;
            $expense->description = $request->note;
            $expense->total_price = $request->totalExpensePrice;
            $expense->expense_date = $request->date;
            $expense->created_by = Auth::user()->id;
            $save = $expense->save();
            $expense_id = $expense->id;
            if($request->type_id || $request->total_price)
            foreach($request->type_id as $key=> $expense)
            {
                $expense_details = new Expense_detail();
                $expense_details->invoice_no = $invoice_no;
                $expense_details->expense_id = $expense_id;
                $expense_details->type_id = $request->type_id[$key];
                $expense_details->total_price = $request->total_price[$key];
                $expense_details->description = $request->description[$key];
                $expense_details->expense_date = $request->date;
                $expense_details->created_by = Auth::user()->id;
                $expense_details->save();
            }

                DB::commit();
                if($save)
                {
                    session(['saleCart' => []]);
                    return redirect()->route('admin.expense.index')->with('success','New Expense is Created successfully!');
                }
                else{
                    return redirect()->back()->with('error','Expense is Not Created!');
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!auth()->user()->can('expense.index')){
            abort(403, 'Unauthorized action.');
        }

        $data['expense'] = Expense::findOrFail($id);
        return view('backend.admin.expense.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->can('expense.index')){
            abort(403, 'Unauthorized action.');
        }
        $expense=Expense::with('expenseDetails')->findOrFail($id);

        $project_company_id=$expense->company_id;
        $company_type_id=$expense->companies->type_id;
        $data['cats']=ExpenseCategory::all();
        $data['types']=ExpenseType::all();
        $data['expense'] = $expense;
        $data['coms'] = Company::where('type_id',$company_type_id)->whereNull('deleted_at')->latest()->get();
        $data['projects'] = project::where('company_id',$project_company_id)->where('working_status',0)->get();
        return view('backend.admin.expense.edit',$data);
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
        if(!auth()->user()->can('expense.index')){
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try
        {
            $input = $request->except('_token');
            $validator = Validator::make($input,[
                    'company_id' => 'required',
                    'project_id' => 'required',
                    'type_id.*' => 'required',
                    'final_total.*' => 'required',
                    'totalExpensePrice' => 'required',
                ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $expense = Expense::find($id);
            $expense->company_id = $request->company_id;
            $expense->project_id = $request->project_id;
            $expense->total_price = $request->totalExpensePrice;
            $expense->expense_date = $request->date;
            $save = $expense->save();
            $expense_id = $expense->id;
            $data=[];
            if($request->type_id || $request->total_price)
            foreach($request->type_id as $key=> $ddd)
            {
                $data[]= new Expense_detail([
                    'invoice_no'=>$expense->invoice_no,
                    'expense_id'=>$expense->id,
                    'type_id'=> $request->type_id[$key],
                    'total_price'=> $request->total_price[$key],
                    'description'=> $request->description[$key],
                    'expense_date'=> $request->expense_date[$key],
                    'created_by'=> Auth::user()->id,
                ]);
            }

            if (!empty($data)) {
            $expense->expenseDetails()->delete();
            $expense->expenseDetails()->saveMany($data);
            }

                DB::commit();
                if($save)
                {
                    session(['saleCart' => []]);
                    return redirect()->back()->with('success','Expense is Update successfully!');
                }
                else{
                    return redirect()->back()->with('error','Expense is Not Update!');
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
        if(!auth()->user()->can('expense.index')){
            abort(403, 'Unauthorized action.');
        }

        $expense=Expense::find($id);
        
        $expense->expenseDetails()->delete();
        $expense->delete();
        
        return redirect()->back()->with('success','Purchase Deleted successfully!');
    }
}
