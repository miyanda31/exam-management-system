<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Paper;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentGradesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($id)
    {
        $terms = Term::select('id','number','calendar_id')->withCount(['calendar as year'=>function($s){
            $s->select('academic');
        }])->get();

        $term = Term::select('number','id','calendar_id')->withCount(['calendar as year'=>function($s){
            $s->select('academic');
        }]);

        $term = \request()->t ? $term->find(\request()->t) : $term->first();

       $scores = Grade::whereTermId($term->id)->whereUserId($id)->withCount(['subject as subject'=>function($s){
            $s->select('short_code');
        }])->with(['grading'=>function($s){
            $s->select('grade','remark','id');
        }])->get();

        $user = User::fullname()->withCount(['form as form'=>function($s){
            $s->select(DB::raw('concat(number,name)'));
        }])->withCount(['form as number'=>function($s){
            $s->select('number');
        }])->find($id);

        $papers = Paper::all();

        return inertia('Students/GradeBook',compact('term','terms','scores','user','papers'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
