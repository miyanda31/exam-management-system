<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Form;
use App\Models\Term;
use App\Models\Time;
use App\Models\Timetable;
use App\Models\User;
use App\Notifications\TimetableChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TimetableController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        $term = Term::status()->first();
        $shifts = Form::groupBy('shift')->orderBy('shift')->get(['shift']);
        $shift = Form::groupBy('shift','number')->select('shift','number')->orderBy('number')->first();

       $classes = Form::whereShift(\request()->s??$shift->shift)->whereNumber(\request()->c??1)->class()->orderBy('name')->get();

        $timetable = Timetable::whereHas('allocation.form',function($q) use ($shift) {
            $q->whereShift(\request()->s??$shift->shift)->whereNumber(\request()->c??1);
        })->with('allocation','allocation.subject:id,short_code')->with(['allocation.user'=>function($q){
            $q->select(DB::raw('concat(fname," ",lname) as name'),'id');
        }])->whereHas('allocation',function($q) use ($term, $shift) {
            $q->whereTermId($term->id);
        })->get();


        $times = Time::select(DB::raw('concat(time_format(start,"%H:%i")," - ",time_format(end,"%H:%i")) as time'),
            DB::raw('concat(time_format(altstart,"%H:%i")," - ",time_format(altend,"%H:%i")) as alttime'),'id','period','type')->orderBy('period')->get();

        $alttimes =Time::whereNotNull('altstart')->whereNotNull('altend')->get()->count();


        $status = Timetable::whereHas('allocation.form',function($q) use ($shift) {
            $q->whereShift(\request()->s??$shift->shift)->whereNumber(\request()->c??1);
        })->whereHas('allocation',function($q) use ($term, $shift) {
            $q->whereTermId($term->id);
        })->whereStatus(0)->count();



        return inertia('Timetable/Main',compact('classes','shift','shifts','timetable','times','alttimes','status'));
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $term = Term::status()->first();

            // check if user has period at that time

        $crash = Allocation::with(['user'=>function($q){
            $q->select(DB::raw('concat(left(fname,1)," ",lname) as name'),'id');
        }])->whereHas('timetable',function ($s) use ($request) {
            $s->whereTimeId($request->time)->where('dayname','<>',$request->day);
        })->whereUserId($request->user_id)->whereTermId($term->id)->first();

        $record = Allocation::whereHas('timetable',function ($s) use ($request) {
                $s->whereTimeId($request->time)->whereDayname($request->day);
            })->with(['user'=>function($q){
                $q->select(DB::raw('concat(left(fname,1)," ",lname) as name'),'id');
            }])->whereId($request->id)->whereTermId($term->id)->first();

            if ($record) {

                $message = 'There is already a subject allocated at this time for this class';
                return redirect()->back()->withErrors(['crashes'=>$message]);
            }
            else if ($crash) {
                $message = $record->user->name. ' already has '.
                $record->subject->name. ' on '
                .$request->timetable->dayname.' at this time in '.$record->form->number.$record->form->name;

                return redirect()->back()->withErrors(['crashes'=>$message]);
            }
            else {

                Timetable::updateOrCreate([
                    'dayname'=>$request->day,
                    'time_id'=>$request->time,
                    'allocation_id'=>$request->id,
                ]);

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

        if (\request()->s){

            return   User::has('allocation')->with('allocation:id,user_id,subject_id,form_id','allocation.subject:id,name,short_code','allocation.form:id,name,number')
                ->search($id)
                ->adminOrTeacher()
                ->select('id',DB::raw('concat(fname," ",lname) as name'))
                ->get();
        }

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
     * @param  Timetable $timetable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timetable $timetable)
    {
        $timetable->update([
            'time_id'=>$request->time,
            'dayname'=>$request->day,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Timetable  $timetable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timetable $timetable)
    {
        $timetable->delete();
    }
}
