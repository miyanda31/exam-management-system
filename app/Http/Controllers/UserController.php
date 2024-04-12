<?php

namespace App\Http\Controllers;

use App\Models\Bookmeta;
use App\Models\Issue;
use App\Models\Form;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{


    public function validation($request)
    {
        $this->validate($request,[
            'fname'=>'sometimes|required',
            'lname'=>'sometimes|required',
            'class'=>'sometimes|required',
        ],[
            'required'=>'This is a required field'
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $pages = 10;
        $students = User::with('form')->profile()->studentOrTeacher();
        if (\request()->n) {
            $pages = request()->n;
        }

        $term  = Term::status()->first();

        if (\request()->s) {
            $students = $students->search(\request()->s);
        }
        if ( \request()->c ) {
            $students = $students->whereHas( 'form', function ( $q ) use ($term) {
                $q->whereId( request()->c )->whereCalendarId($term->calendar_id);
            } );
        }

        if ( \request()->g ) {
            $students = $students->whereGender(request()->g);
        }

        if ( \request()->t ) {
            $students = $students->whereType(request()->t);
        }


        $students = $students->orderBy('lname')->orderBy('gender')->paginate($pages);

        $students->setPath(\request()->fullUrl());

        $classes = Form::class()->get();

        return inertia('Users', compact('students','classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = User::updateOrCreate([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'gender'=>$request->gender,
            'type'=>$request->type,
        ]);

        if ($request->type == 'Student') {
            $term = Term::status()->first();
            $user->form()->sync($request->class,['calendar_id'=>$term->calendar_id]);
        }

        if ($request->hasFile('photo')){
            $name = $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('/img/'),$name);
            $user->update([
                'avatar'=>$name
            ]);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function show(User $user)
    {

        $terms = Term::whereNotNull('id');

        $terms = \request()->t ? $terms->whereNumber(\request()->t)->whereYear('opening', \request()->y) : $terms->status();

        $terms = $terms->first();
        // get records for user

        $months = Issue::select(DB::raw('DATE_FORMAT(created_at,"%M, %Y") as name,count(*) as total,DATE_FORMAT(created_at,"%m") as month'))
            ->groupBy(DB::raw('DATE_FORMAT(created_at,"%M, %Y"),DATE_FORMAT(created_at,"%m")'))->whereBetween('created_at',[$terms->opening,$terms->closing])->whereUserId($user->id)->get();
        $logs = [];

        foreach ($months as  $month) {
            $logs[$month->name] = Issue::with(['bookmeta'=>function ($q)  {
                $q->with('book:id,title,class');
            }])->withTrashed()->whereUserId($user->id)->select(DB::raw('DATE_FORMAT(deleted_at,"%d %M %Y") as day'),DB::raw('DATE_FORMAT(created_at,"%d %M") as month'),DB::raw('DATE_FORMAT(created_at,"%d %M %Y") as loaned'),'bookmeta_id','deleted_at','return_date')->whereMonth('created_at',$month->month)->whereYear('created_at',$terms->opening)->get();
        }

        return inertia('History',compact('user','logs','terms'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $user->update([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'gender'=>$request->gender,
            'type'=>$request->type,
        ]);

        if ($request->hasFile('photo')){
            unlink(public_path('/img/').$user->avatar) ;

            $name = $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('/img/'),$name);

            $user->update([
                'avatar'=>$name
            ]);
        }
        if ($request->type == 'Student') {
            $term = Term::status()->first();
            $user->form()->syncWithPivotValues($request->class,['calendar_id'=>$term->calendar_id]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->form()->detach();
        $user->issue()->delete();
        $user->delete();
    }
}
