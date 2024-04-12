<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Reason;
use App\Models\School;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        $term = Term::status()->first();

        //get parents
        $users =  User::when(\request()->q,function ($q){
            return  $q->search(\request()->q);
        })->select('fname','lname','student_id','id','gender')->with('form');

        $classes = Form::class()->get();

        if (\request()->u) {
            $users =  $users->search(\request()->s);
        }

        if ( \request()->f ) {
            $users = $users->whereHas( 'form', function ( $q ) use ($term) {
                $q->whereId( request()->f )->whereCalendarId($term->calendar_id);
            } );
        }

        if ( \request()->g ) {
            $users = $users->whereGender(request()->g);
        }

        $users =  $users->student()->active()->orderBy('lname')->orderBy('gender')->latest()->paginate(\request()->n);

        $users->setPath(\request()->fullUrl());

        if (request()->a) return response()->json(compact('users','classes'));

        return inertia('Students/Registration',compact('users','classes'));
    }

    public function validation($request)
    {
        $fields =[
            'fname.required'=>'Field is mandatory',
            'lname.required'=>'Field is mandatory',
        ];
        $rules = [
            'fname'=>'required',
            'lname'=>'required',
            'class'=>'required',
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

        $school = School::first();

        $id = explode(' ',$school->name)[0][0];

        $user =   User::updateOrcreate([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'gender'=>$request->gender,
            'student_id'=>$id.'SS/'.random_int(1000,20000),
            'type'=>'Student',
            'school_id'=>Auth::user()->school_id,
        ]);

        $user->form()->attach($request->class);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($user)
    {
        $user = User::profile()->with('guardian:id,fname,lname','bursary:id,name','form:id,name,number')->find($user);
       return inertia('Students/StudentPage',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   int $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $user)
    {
        $this->validation($request);

        $user = User::find($user);

        $user->update([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'gender'=>$request->gender,
        ]);

        $user->form()->detach();
        $user->form()->attach($request->class);


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

        $user->form()->detach();
        $user->grade()->delete();
        $user->score()->delete();
        $user->forceDelete();
    }
}
