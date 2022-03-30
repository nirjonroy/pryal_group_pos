<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('user.view')){
            abort(403, 'Unauthorized action.');
        } 

        $users=User::all();
        return view('user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('user.view')){
            abort(403, 'Unauthorized action.');
        } 

        $roles=Role::all();
        return view('user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('user.view')){
            abort(403, 'Unauthorized action.');
        } 

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user=User::create([
            'name' => request()->name,
            'email' => request()->email,
            'phone' => request()->phone,
            'password' => Hash::make(request()->password),
        ]);

        if (!empty(request()->roles)) {
            $user->assignRole(request()->roles);
        }
        return redirect()->route('user.index')->with('success','User is Created successfully!');
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
    if(!auth()->user()->can('user.view')){
            abort(403, 'Unauthorized action.');
    } 
          $roles=Role::all();
        $user=User::find($id);
        return view('user.edit',compact('user','roles'));
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
        if(!auth()->user()->can('user.view')){
            abort(403, 'Unauthorized action.');
        } 

        $user=User::find($id);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
        ]);

        $user->name=request()->name;
        $user->email=request()->email;
        $user->phone=request()->phone;
        if(request()->password){
           $user->password= Hash::make(request()->password);
        }

        $user->save();

        $user->roles()->detach();
        if (!empty(request()->roles)) {
            $user->assignRole(request()->roles);
        }
        return redirect()->route('user.index')->with('success','User is Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=User::findOrFail($id);
        $data->delete();
        return redirect()->back()->with('success',' Delete successfully Done!');
    }
}
