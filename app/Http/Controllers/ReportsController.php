<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Grade;
use App\Models\Grading;
use App\Models\School;
use App\Models\Score;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $form = Form::withCount(['user as enrollment'=>function( $q ){
            $q->active()->student();
        }])->find(\request()->f);

        $schoolId = $form->school_id;

        $school = School::find($schoolId);


        if (\request()->r) {
            $aggregator = Subject::whereHas('schools',function ($s) use ($schoolId) {
                $s->whereId($schoolId);
            })->whereName("english")->first();

          $users = User::simple()
             ->with(['score'=>function ($q)  {
                $q->whereTermId(request()->t);
            }])
             ->withCount(['score as class_position'=>function ($q)  {
                $q->select('class_position');
            }])
             ->withCount(['score as position'=>function ($q)  {
                $q->select('position');
            }])
             ->withCount(['score as remark'=>function ($q)  {
                $q->select('remark');
            }])
             ->withCount(['score as passed'=>function ($q)  {
                $q->select('passed');
            }])
             ->with(['grades'=>function ($q)  {
                $q->whereHas('allocation',function ($s){
                    $s->whereTermId(request()->t);
                });
            }])
             ->whereHas('form',function ( $q ){
                $q->whereId(\request()->f);
            })->active()->student()->orderBy(DB::raw('ISNULL(position)'),'ASC')->orderBy('position')->get();


            $term = Term::find(request()->t);
            $nextTerm = Term::whereCalendarId($term->calendar_id)->whereNumber($term->number+1)->first();



           $grading = Grading::whereType($form->number>2?'MSCE':'JCE')->whereSchoolId($schoolId)->get();

           $fail = Grading::whereType($form->number>2?'MSCE':'JCE')->whereSchoolId($schoolId)->whereGrade($form->number>2?9:'F')->first();

            $name = PDF::loadView('assessments.reports',compact('users','term','form','school','nextTerm','grading','fail','aggregator'));

            $path = public_path().'/files/'.$school->name.' Form '.$form->number.$form->name.'.pdf';


            return $name->stream($path,$school->name.' Form '.$form->number.$form->name.'.pdf');
        }

        if (\request()->m) {
            $users = User::simple()->with(['grades'=>function ( $q )  {
                $q->select('score','user_id','grading_id','allocation_id')->whereHas('allocation',function ($s){
                    $s->whereTermId(request()->t)->whereFormId(\request()->f);
                })->withCount(['grading as grade'=>function ( $q ){
                    $q->select('grade');
                }]);
            }])->whereHas('form',function ( $q ) {
                $q ->whereId(\request()->f);
            })->whereSchoolId($schoolId)->student()->active()->get();



            $subjects = Subject::whereHas('schools',function ($s) use ($schoolId) {
                $s->whereSchoolId($schoolId);
            })->get(['id','short_code']);

//            return response()->json($users);
            $name = PDF::loadView('assessments.grades',compact('users','form','subjects'),[],['format'=>'A4-L']);


            $path = public_path().'/files/'.$school->name.' Form'.$form->number.$form->name.' Marksheet.pdf';

            $name->save($path);

           return response()->download($path);

        }

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
     * @param  int  $grade
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function show(int $grade)
    {

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
