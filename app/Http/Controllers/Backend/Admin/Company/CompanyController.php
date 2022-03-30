<?php

namespace App\Http\Controllers\Backend\Admin\Company;

use App\Http\Controllers\Controller;
use App\Model\Backend\Admin\Company\Company;
use App\CompanyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
   
        

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('company.index')){
            abort(403, 'Unauthorized action.');
        }

        $types=CompanyType::orderBy('name','asc')->get();
        
        $query1=Company::orderby('name','asc');
        if(request()->type !=''){
                    $query1->where('type_id',request()->type);
                }
        $coms=$query1->get();
        $query = Company::with('type')->whereNull('deleted_at');
                if(request()->type !=''){
                    $query->where('type_id',request()->type);
                }
                
                if(request()->company_id !=''){
                    $query->where('id',request()->company_id);
                }
        $companies=$query->latest()->get();
        return view('backend.admin.company.index',compact('companies','types','coms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('company.create')){
            abort(403, 'Unauthorized action.');
        }

        $types=CompanyType::all();
        return view('backend.admin.company.create',compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('company.create')){
            abort(403, 'Unauthorized action.');
        }

        $input = $request->except('_token');
        $validator = Validator::make($input,[
                'name' => 'required|min:2|max:255',
                'contract_person' => 'required|min:2|max:150',
                'contract_phone' => 'required|min:6|max:15|unique:companies,contract_phone',
                //'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
                'address' => 'nullable',
                'type_id' => 'required|numeric',
            ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $company = new Company();
        $company->name = $request->name;
        $company->contract_person = $request->contract_person;
        $company->contract_phone = $request->contract_phone;
        $company->type_id = $request->type_id;
        $company->address = $request->address;
        $company->created_by = Auth::user()->id;
        $save = $company->save();
        if($save)
        {
            return redirect()->route('admin.company.index')->with('success','New Company is created successfully!!');
        }
        else{
            return redirect()->back()->with('error','New Company is not created!!');
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
        if(!auth()->user()->can('company.index')){
            abort(403, 'Unauthorized action.');
        }

         $data['company'] = Company::find($id);
        return view('backend.admin.company.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->can('company.edit')){
            abort(403, 'Unauthorized action.');
        }

        $data['types']=CompanyType::all();
        $data['company'] = Company::findOrFail($id);
        return view('backend.admin.company.edit',$data);
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
        if(!auth()->user()->can('company.edit')){
            abort(403, 'Unauthorized action.');
        }

        $input = $request->except('_token');
        $validator = Validator::make($input,[
                'name' => 'required|min:2|max:255',
                'contract_person' => 'required|min:2|max:150',
                'contract_phone' => 'required|min:6|max:15|unique:companies,contract_phone,'.$id,
                //'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
                'address' => 'nullable',
                 'type_id' => 'required|numeric',
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
        $company = Company::findOrFail($id);
        $company->name = $request->name;
        $company->contract_person = $request->contract_person;
        $company->contract_phone = $request->contract_phone;
        $company->type_id = $request->type_id;
        $company->address = $request->address;
        $company->created_by = Auth::user()->id;
        $save = $company->save();
        if($save)
        {
            return redirect()->route('admin.company.index')->with('success','Company is Updated successfully!!');
        }
        else{
            return redirect()->back()->with('error','New Company is not created!!');
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
        if(!auth()->user()->can('company.delete')){
            abort(403, 'Unauthorized action.');
        }

        $data=Company::findOrFail($id);
        $data->delete();
        return redirect()->route('admin.company.index')->with('success',' Delete successfully Done!');
    }
}
