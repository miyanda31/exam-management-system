<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolCalendarController extends Controller
{

    public function validation($request)
    {
        $rules =[
            'opening'=>'required',
            'closing'=>'required',
        ];

        $this->validate($request,$rules,['required'=>"Field is required"]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
            $calendar = Calendar::with('term')->whereSchoolId(Auth::user()->school_id)->whereStatus(1)->first();

            return response()->json($calendar);
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

        Calendar::updateOrCreate([
            'opening'=>$request->opening,
            'closing'=>$request->closing,
            'academic'=>$request->year,
            'school_id'=>Auth::user()->school_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Calendar  $calendar
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function show(Calendar $calendar)
    {
        $terms = Term::whereCalendarId($calendar->id)->get();

        return inertia('Settings/Terms',compact('calendar','terms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar)
    {
        $this->validation($request);

        if ($request->s) {
            $calendar->update([
                'status'=>$request->s
            ]);

            return ;
        }


        $calendar->update([
            'opening'=>$request->opening,
            'closing'=>$request->closing,
            'academic'=>$request->year,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        //
    }
}
