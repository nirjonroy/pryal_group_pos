<?php

namespace App\Http\Controllers;
use App\BankHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Carbon\Carbon;
class BankHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(!auth()->user()->can('bank_statement.index')){
            abort(403, 'Unauthorized action.');
        }

        $bank_in=BankHistory::where('type','in')->orderby('id', 'desc')->get();
        return view('backend.admin.bank-in.index',compact('bank_in'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('bank_statement.create')){
            abort(403, 'Unauthorized action.');
        }

        return view('backend.admin.bank-in.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('bank_statement.create')){
            abort(403, 'Unauthorized action.');
        }
    
    
        $input = $request->except('_token');
        $validator = Validator::make($input,[
                'bank_name' => 'required',
                'amount' => 'numeric|'
            ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

         $amount=(blanceInHand()+$request->amount);
        $data=[
            'bank_name'=>$request->bank_name,
            'responsible_person'=>$request->responsible_person,
            'ac_no'=>$request->ac_no,
            'note'=>$request->note,
            'amount'=>$request->amount,
            'user_id'=>Auth::user()->id,
            'created_at'=>$_POST['date'].' '.date('h:i:s'),
            'type'=>'in',
            'hand'=>$amount,

        ];
        
        
          DB::table('bank_histories')->insert($data);
        return redirect()->route('bank-in.index')->with('success','New Bank Amount In Amount is Created successfully!');    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
       if(!auth()->user()->can('bank_statement.edit')){
            abort(403, 'Unauthorized action.');
        }
        $data=BankHistory::findOrFail($id);
         return view('backend.admin.bank-in.edit',compact('data'));
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

       $getData = BankHistory::findOrFail($id);
       $amount =  $getData->amount;
       $hand = $getData->hand;
       $req_amount = $request->amount;


        $get_upper_data = BankHistory::where('id', '>' ,$getData->id)->get();

      
        if ($getData->type == 'in') {
                   if ($req_amount>$amount) 
                   {

                    $amount_greater = $req_amount - $amount;

                    $data=[
                        'bank_name'=>$request->bank_name,
                        'amount'=>$req_amount,
                        'hand'=>$getData->hand + $amount_greater,
                        'note'=>$request->note,
                        'created_at'=>$_POST['date'].' '.date('h:i:s'),
                        
                     ];

                     DB::table('bank_histories')->where('id',$id)->update($data);

                     if (!empty($get_upper_data)) {
                          foreach ($get_upper_data as  $data) {
                          
                          
                          $data->hand = $data->hand +  $amount_greater;
                          $data->save();

                     }
                    }
                 
                    

                   
                }elseif ($req_amount<$amount) {

                  $amount_less =  $amount - $req_amount;

                   $data=[
                        'bank_name'=>$request->bank_name,
                        'amount'=>$req_amount,
                        'hand'=>$getData->hand - $amount_less,
                        'note'=>$request->note,
                        'created_at'=>$_POST['date'].' '.date('h:i:s'),
                        
                     ];

                     DB::table('bank_histories')->where('id',$id)->update($data);

                     if (!empty($get_upper_data)) {
                         foreach ($get_upper_data as  $data) {
                          
                          
                          $data->hand = $data->hand -  $amount_less;
                          $data->save();

                     }
                    }

                   

                }elseif ($req_amount==$amount) {
                   
                    $data=[
                        'bank_name'=>$request->bank_name,
                        'amount'=>$req_amount,
                        'hand'=>$getData->hand,
                        'note'=>$request->note,
                        'created_at'=>$_POST['date'].' '.date('h:i:s'),
                        
                     ];

                     DB::table('bank_histories')->where('id',$id)->update($data);
                }
         
        }elseif($getData->type == 'out'){


                 if ($req_amount>$amount) {

                    $amount_greater = $req_amount - $amount;

                    $data=[
                        'bank_name'=>$request->bank_name,
                        'amount'=>$req_amount,
                        'hand'=>$getData->hand - $amount_greater,
                        'note'=>$request->note,
                        'created_at'=>$_POST['date'].' '.date('h:i:s'),
                        
                     ];

                     DB::table('bank_histories')->where('id',$id)->update($data);

                     
                    if (!empty($get_upper_data)) {
                        foreach ($get_upper_data as  $data) {
                          
                          
                          $data->hand = $data->hand -  $amount_greater;
                          $data->save();

                     }
                    }
                     

                   
                }elseif ($req_amount<$amount) {

                  $amount_less =  $amount - $req_amount;

                   $data=[
                        'bank_name'=>$request->bank_name,
                        'amount'=>$req_amount,
                        'hand'=>$getData->hand + $amount_less,
                        'note'=>$request->note,
                        'created_at'=>$_POST['date'].' '.date('h:i:s'),
                        
                     ];

                     DB::table('bank_histories')->where('id',$id)->update($data);

                   if (!empty($get_upper_data)) {
                        foreach ($get_upper_data as  $data) {
                          
                          
                          $data->hand = $data->hand +  $amount_less;
                          $data->save();

                     }
                   }

                }elseif ($req_amount==$amount) {
                   
                    $data=[
                        'bank_name'=>$request->bank_name,
                        'amount'=>$req_amount,
                        'hand'=>$getData->hand,
                        'note'=>$request->note,
                        'created_at'=>$_POST['date'].' '.date('h:i:s'),
                        
                     ];

                     DB::table('bank_histories')->where('id',$id)->update($data);
                }
         
        }
       

         return redirect()->route('bank-in.index')->with('success',' Update successfully Done!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('bank_statement.index')){
            abort(403, 'Unauthorized action.');
        }
        
        $data=BankHistory::findOrFail($id);
         $get_upper_data = BankHistory::where('id', '>' ,$id)->get();
         if ($data->type== 'in') {
             if (!empty($get_upper_data)) {
                 foreach ($get_upper_data as  $value) {
                    $value->hand = $value->hand - $data->amount;
                    $value->save();
                 }
             }
         }elseif($data->type== 'out') {
             if (!empty($get_upper_data)) {
                 foreach ($get_upper_data as  $value) {
                    $value->hand = $value->hand + $data->amount;
                    $value->save();
                 }
             }
         }

        $data->delete();
        return redirect()->route('bank-in.index')->with('success',' Delete successfully Done!');
    }

    public function bankStatement(){
        $query=BankHistory::orderby('id','asc');
        
        if(request()->start_date !='' and request()->end_date !=''){
             $query->whereBetween('created_at',[request()->start_date.' 00:00:00',request()->end_date.' 23:59:00']);
         }



        
        if(request()->alldata){
            $data['banks']=$query->paginate(1000);
          }else{
            $data['banks']=$query->paginate(20);

          }
          
        $totals = BankHistory::orderby('id','asc')->get();

        return view('backend.admin.bank-in.bank_statement',compact('totals'),$data);
    }






    // Last 15 Days Statements 

    public function bankStatementByDay(){

        $all = BankHistory::all();
        $count = ($all->count()) - 5;


        $data['banks'] = BankHistory::skip($count)->take(5)->get();

        $in=0;
        $out=0;
        foreach ($all as $key => $value) {
           if ($key==$count) {
               break;
           }
           $in +=$value->type=='in'?$value->amount:0;
           $out +=$value->type=='out'?$value->amount:0;
        }
        $data['hand']=$in -$out;
        
        return view('backend.admin.bank-in.bank_statement_by_day',$data);
    }

    public function getProject(){

        $data=DB::table('projects')->where('company_id',request()->id)->where('working_status','!=',1)->orderBy('name','asc')->pluck("name","id");

        return json_encode($data);


    }

    public function getsupplier(){

        $data=DB::table('suppliers')->where('type_id',request()->id)->orderBy('name','asc')->pluck("name","id");

        return json_encode($data);


    }
    
    public function getCompany(){

        $data=DB::table('companies')->where('type_id',request()->type_id)->orderBy('name','asc')->pluck("name","id");

        return json_encode($data);


    }
    
    
    public function getProjectNew(){

        $data=DB::table('projects')->where('company_id',request()->id)->where('working_status','!=',1)->orderBy('name','asc')->pluck("id","name");

        return json_encode($data);


    }

    public function getsupplierNew(){

        $data=DB::table('suppliers')->where('type_id',request()->id)->orderBy('name','asc')->pluck("id","name");

        return json_encode($data);


    }

    public function getcustomerNew(){

        $data=DB::table('customers')->where('type_id',request()->id)->orderBy('name','asc')->pluck("id","name");

        return json_encode($data);
    }
    
    public function getCompanyNew(){

        $data=DB::table('companies')->where('type_id',request()->type_id)->orderBy('name','asc')->pluck("id","name");

        return json_encode($data);


    }
}
