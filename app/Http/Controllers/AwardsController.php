<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Form;
use App\Models\Grade;
use App\Models\Score;
use App\Models\Term;
use Illuminate\Http\Request;

class AwardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {


        $term = Term::status()->first(['id','number']) ;
        $terms = Term::withCount(['calendar as year'=>function($s){
            $s->select('academic');
        }])->status()->get(['id','number','calendar_id']) ;

        $grades = Score::with('user:id,fname,lname,gender')
            ->whereHas('form',function ( $q ) {
                $q->whereNumber(\request()->f?\request()->f:1);
            })
            ->orderByDesc('aggregate')
            ->select('id','user_id','position','aggregate');

        $grades = \request()->t ? $grades->whereTermId(\request()->t) : $grades->whereTermId($term->id??0);


        $grades = $grades ->whereStatus(1)->take(10)->get();

        if (\request()->a) return response()->json(compact('term','grades'));


        return inertia('Examinations/Awards',compact('term','terms','grades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grade $grade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade)
    {
        //
    }
}
