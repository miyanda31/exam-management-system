<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $calendar = Calendar::withCount('term as number')->get();

       return $calendar;
    }

    public function validation($request)
    {
        $rules =[
            'number'=>'required',
            'opening'=>'required',
            'closing'=>'required',
        ];

        $this->validate($request,$rules);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);

        Term::updateOrCreate([
            'number'=>$request->number,
            'opening'=>$request->opening,
            'closing'=>$request->closing,
            'calendar_id'=>$request->calendar,
            'status'=> $request->status??0
        ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function show(Term $term)
    {

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Term $term)
    {
    	if ($request->s) {
    		$term->update([
    			'status'=>$request->s
		    ]);

    		return ;
	    }


       $term->update([
           'opening'=>$request->opening,
           'closing'=>$request->closing,
           'number'=>$request->number,
       ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $term)
    {
        //
    }
}
