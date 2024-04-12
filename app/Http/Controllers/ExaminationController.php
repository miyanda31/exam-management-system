<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExaminationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $term = Term::status()->first();

        if (\request()->p) {
            Examination::whereTermId($term->id)->update([
                'status'=>1
            ]);

            return ;
        }
        if (\request()->t) {
            $examinations = Examination::with('user','users')->whereBetween('start',[request()->s,request()->e])->get();
            return response()->json($examinations);
        }

        $users = User::fullname()->adminOrTeacher()->active()->get();
        $subjects = Subject::has('schools')->get();
        $status = Examination::whereStatus(0)->count();
        $publish = Examination::whereStatus(1)->count();

        return inertia('Timetable/Examinations',compact('users','subjects','status','publish','term'));
    }

    public function validation($request)
    {
        $messages =[
            'end.required'=>'Ending period time is not set',
            'start.required'=>'Starting period time is not set',
            'subject.required'=>'Subject is not set',
        ];
        $rules =[
            'end'=>'sometimes|required',
            'start'=>'sometimes|required',
            'subject'=>'sometimes|required',
        ];
        $this->validate($request,$rules,$messages);
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
        $event = Examination::updateOrCreate([
            'title'=>$request->paper?$request->subject.' '.$request->paper:$request->subject,
            'start'=>$request->day.' '.$request->start,
            'end'=>$request->day.' '.$request->end,
            'form'=>$request->form,
            'paper'=>$request->paper,
            'status'=>0,
            'term_id'=>Term::status()->first()->id,
        ]);

            $event->user()->attach($request->supervisors,['cat'=>'supervisor']);
            $event->user()->attach($request->tod,['cat'=>'tod']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Examination  $examination
     * @return \Illuminate\Http\Response
     */
    public function show(Examination $examination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Examination  $examination
     * @return \Illuminate\Http\Response
     */
    public function edit(Examination $examination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Examination  $examination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Examination $examination)
    {
      if ($request->u) {
          $examination->update([
              'start'=>$request->s,
              'end'=>$request->e,
          ]);
          return ;
      }

        $examination->update([
            'title'=>$request->paper?$request->subject.' '.$request->paper:$request->subject,
            'start'=>$request->day.' '.$request->start,
            'end'=>$request->day.' '.$request->end,
            'form'=>$request->form,
            'paper'=>$request->paper,
        ]);

        $examination->user()->syncWithPivotValues($request->supervisors,['cat'=>'supervisor']);
        $examination->users()->syncWithPivotValues($request->tod,['cat'=>'tod']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Examination  $examination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Examination $examination)
    {
        $examination->with('user','users')->destroy();
    }
}
