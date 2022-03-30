<?php

namespace App\Http\Controllers;
use App\BankHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
class BankHistoryOutController extends Controller
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

        $bank_out=BankHistory::where('type','out')->orderby('id','desc')->get();
        return view('backend.admin.bank-out.index',compact('bank_out'));
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

        $amount_in=BankHistory::where('type','in')->sum('amount');
        $amount_out=BankHistory::where('type','out')->sum('amount');
        $amount_has_total = $amount_in - $amount_out;

        return view('backend.admin.bank-out.create',compact('amount_out','amount_in','amount_has_total'));
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
        $amount=(blanceInHand()-$request->amount);
        $data=[
            'bank_name'=>$request->bank_name,
            'responsible_person'=>$request->responsible_person,
            'ac_no'=>$request->ac_no,
            'note'=>$request->note,
            'amount'=>$request->amount,
            'user_id'=>Auth::user()->id,
            'created_at'=>$_POST['date'].' '.date('h:i:s'),
            'hand'=>$amount,
            'type'=>'out'

        ];

        
             DB::table('bank_histories')->insert($data);
              return redirect()->route('bank-out.index')->with('success','New Bank Amount In Amount is Created successfully!');
         

      
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
    {   if(!auth()->user()->can('bank_statement.index')){
            abort(403, 'Unauthorized action.');
        }

        $data=BankHistory::findOrFail($id);
         return view('backend.admin.bank-out.edit',compact('data'));
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
        if(!auth()->user()->can('bank_statement.index')){
            abort(403, 'Unauthorized action.');
        }

         $getData = BankHistory::findOrFail($id);
         $amount = $getData->amount;
         $hand = $getData->hand;
         
         dd($hand);

       $data=[
            'bank_name'=>$request->bank_name,
            'note'=>$request->note,
            'amount'=>$request->amount,
            'created_at'=>$_POST['date'].' '.date('h:i:s'),
            'hand'=>$request->hand
        ];

        

         return redirect()->route('bank-out.index')->with('success',' Update successfully Done!');
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
        $data->delete();
        return redirect()->route('bank-out.index')->with('success',' Delete successfully Done!');
    }
}
