<?php

namespace App\Http\Controllers\Backend\Admin\Project;

use App\Http\Controllers\Controller;
use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Project\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\ProjectType;
use DB;
use App\User;
class ProjectController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if(!auth()->user()->can('project.index')){
            abort(403, 'Unauthorized action.');
        }

        $com=Company::orderBy('name','asc');
            if(request()->com_type_id !=''){
                $com->where('type_id',request()->com_type_id);
            }
        $companies=$com->get();
            $types=ProjectType::orderby('name','asc')->get();
            $query_ps=Project::with('user');

            if(request()->status !=''){
                $query_ps->where('working_status',request()->status);
            }
            
            if(request()->company_id !=''){
                $query_ps->where('company_id','like', request()->company_id);
            }

            $ps=$query_ps->orderby('name','asc')->get();
            $query=Project::with('user','companies','projectPayment','purchase','type','expense');
            if(!empty($_GET['search'])){
                $query->where('projects.name','like', '%'.$_GET['search'].'%');
            }

            if(request()->company_id !=''){
                $query->where('projects.company_id','like', request()->company_id);
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

           

            if(request()->alldata){
                $projects=$query->orderBy('name', 'asc')->paginate(5000);
              }else{
               $projects=$query->orderBy('name', 'asc')->paginate(50);

              }

            $status=\App\Unit::getStatus();
        return view('backend.admin.project.index',compact('projects','companies','ps','types','status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
         if(!auth()->user()->can('project.create')){
            abort(403, 'Unauthorized action.');
        }
         $data['partners'] = User::role('Partners')->get();
         
        $data['types']=ProjectType::orderBy('name','asc')->get();
        $data['companies'] = Company::whereNull('deleted_at')->orderBy('name','asc')->latest()->get();
        return view('backend.admin.project.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if(!auth()->user()->can('project.create')){
            abort(403, 'Unauthorized action.');
        }

        $input = $request->except('_token');
        $validator = Validator::make($input,[
                'name' => 'required|min:2|max:250',
                'company_id' => 'required|numeric',
                'project_type_id' => 'required|numeric',
                'project_value' => 'required|numeric',
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
        $project = new Project();
        $project->name = $request->name;
        $project->company_id = $request->company_id;
        $project->project_type_id = $request->project_type_id;
        $project->project_value = $request->project_value;
        $project->start_date = $request->start_date ?date('Y-m-d', strtotime($request->start_date)):null;
        $project->end_date = $request->end_date ?date('Y-m-d', strtotime($request->end_date)):null;
        $project->created_by = Auth::user()->id;
        $project->project_partner = $request->project_partner?$request->project_partner:null;
        $save = $project->save();
        if($save)
        {
            return redirect()->route('admin.project.index')->with('success','New Project is created successfully!!');
        }
        else{
            return redirect()->back()->with('error','New Project is not created!!');
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
        if(!auth()->user()->can('project.edit')){
            abort(403, 'Unauthorized action.');
        }
        $data['partners'] = User::role('Partners')->get();
        $data['types']=ProjectType::all();
        $data['companies'] = Company::whereNull('deleted_at')->latest()->get();
        $data['project'] = Project::findOrFail($id);
        return view('backend.admin.project.edit',$data);
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
         if(!auth()->user()->can('project.edit')){
            abort(403, 'Unauthorized action.');
        }

        $input = $request->except('_token');
        $validator = Validator::make($input,[
                'name' => 'required|min:2|max:250',
                'company_id' => 'required|numeric',
                'project_type_id' => 'required|numeric',
                'project_value' => 'required|numeric',
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $project =  Project::findOrFail($id);
        $project->name = $request->name;
        $project->company_id = $request->company_id;
        $project->project_type_id = $request->project_type_id;
        $project->project_value = $request->project_value;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->created_by = Auth::user()->id;
        $project->project_partner = $request->project_partner;
        $save = $project->save();
        if($save)
        {
            return redirect()->route('admin.project.index')->with('success','Project is Updated successfully!!');
        }
        else{
            return redirect()->back()->with('error',' Project is not Updated!!');
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
        //
    }


    public function getPaymentModal($id){
        $project=$query=Project::with('user','companies','projectPayment')->find($id);
        $methods=DB::table('payment_methods')->get();
        return view('backend.admin.project.payment_modal',compact('methods','project'));
    }

    public function projectPayment(){

        $data=[
                'payment_method_id'=>$_POST['payment_method_id'],
                'project_id'=>$_POST['project_id'],
                'company_id'=>$_POST['company_id'],
                'payment_amount'=>$_POST['payment_amount'],
                'note'=>$_POST['note'],
                'payment_accepted_by'=>Auth::user()->id,
                'created_at'=>$_POST['date'].' '.date('h:i:s'),
            ];
        DB::table('project_payment_histories')->insert($data);
        return redirect()->back()->with('success','Payment successfully Done!!');
    }

    public function getDetailsModal($id){
        $project=$query=Project::with('user','companies','projectPayment','projectPayment.method')->find($id);
        return view('backend.admin.project.details_modal',compact('project'));
    }

    public function updateStatus(){

        $date=request()->date;
        $st=request()->status;
        $id=request()->id;
        Project::where('id',$id)->update(['working_status'=>$st,'date'=>$date]);
        return redirect()->route('admin.project.index')->with('success','Project Status Updated!!');

    }

    public function receivedPayment(){

        if(!auth()->user()->can('received_payment.create')){
            abort(403, 'Unauthorized action.');
        }


        $projects=Project::orderBy('name','asc')->where('working_status',0)->get();
        $com=Company::orderby('name','asc');
                if(request()->type_id !=''){
                $com->where('type_id',request()->type_id);
            }
        $coms=$com->get();
        return view('backend.admin.project.received_payment',compact('projects','coms'));
    }

    public function getProject(){


        if(!empty(request()->project_id and request()->company_id)){
            $project=$query=Project::with('user','companies','projectPayment')
                    ->where(['id'=>request()->project_id,'company_id'=>request()->company_id])
                    ->where('working_status','!=',1)
                    ->first();
            $methods=DB::table('payment_methods')->get();
            return view('backend.admin.project.payment_form',compact('methods','project'));
        }
        
    }


    public function getStatusModal($id){

        $project=Project::find($id);
        $status=\App\Unit::getStatus();
        return view('backend.admin.project.status',compact('project','status'));
    }
}
