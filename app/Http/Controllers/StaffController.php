<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Profile;
use App\Models\Reason;
use App\Models\Role;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        //get parents
        $users =  User::when(\request()->q,function ($q){
          return  $q->search(\request()->q);
        })->select('fname','lname','id','gender');


        if ( \request()->g ) {
            $users = $users->whereGender(request()->g);
        }

        if ( \request()->r ) {
            $users = $users->whereRoleId(request()->r);
        }

        $users =  $users->adminOrTeacher()->orderBy('lname')->orderBy('gender')->latest()->paginate(\request()->n??15);

        $users->setPath(\request()->fullUrl());

        if (request()->a) return response()->json(compact('users'));

        return inertia('Staff/Registration',compact('users'));
    }

    public function validation($request)
    {
        $fields =[
            'required'=>'Field is mandatory',

        ];
        $rules =[
            'fname'=>'required',
            'lname'=>'required',

        ];
        $this->validate($request,$rules,$fields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);

       User::updateOrcreate([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'gender'=>$request->gender,
            'type'=>'Teacher',
            'school_id'=>Auth::user()->school_id,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {

        $this->validation($request);

        $user = User::find($user);

        $user->update([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'gender'=>$request->gender,

        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $user)
    {
        $user = User::find($user);

        $user->allocation()->delete();
        $user->forceDelete();
    }
}
