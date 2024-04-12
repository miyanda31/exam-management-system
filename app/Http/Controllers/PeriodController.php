<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Period;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeriodController extends Controller
{

    public function validation($request)
    {
        $messages =[
            'end.required'=>'Ending period time is not set',
            'start.required'=>'Starting period time is not set',
        ];
        $rules =[
            'end'=>'sometimes|required',
            'start'=>'sometimes|required',

        ];
        $this->validate($request,$rules,$messages);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        $periods = Time::orderBy('period')->get();
        $total = Form::select('shift')->count();

        return inertia('Timetable/Periods',compact('periods','total'));
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
        $first = Time::orderByDesc('period')->first();

        Time::updateOrCreate([
            'start'=>Carbon::parse($request->start)->toTimeString(),
            'end'=>Carbon::parse($request->end)->toTimeString(),
            'altstart'=>$request->altstart==''?null:Carbon::parse($request->altstart)->toTimeString(),
            'altend'=>$request->altend==''?null:Carbon::parse($request->altend)->toTimeString(),
            'type'=>$request->type,
            'period'=>$first?$first->period+1:1,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Period  $period
     * @return \Illuminate\Http\Response
     */
    public function show(Period $period)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Time  $period
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Time $period)
    {
        if ($request->t) {

            $period->update([
                'period'=>$request->end+1,
            ]);

            Time::find($request->altend)->update([
                'period'=>$request->start+1,
            ]);

            return;
        }

        $period->update([
            'start'=>Carbon::parse($request->start)->toTimeString(),
            'end'=>Carbon::parse($request->end)->toTimeString(),
            'altstart'=>$request->altstart==''?null:Carbon::parse($request->altstart)->toTimeString(),
            'altend'=>$request->altend==''?null:Carbon::parse($request->altend)->toTimeString(),
            'type'=>$request->type,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Time  $period
     * @return \Illuminate\Http\Response
     */
    public function destroy(Time $period)
    {
        $period->with('timetable')->delete();
    }
}
