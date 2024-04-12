<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Form;
use App\Models\Grading;
use App\Models\Paper;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $term = Term::status()->whereHas('calendar',function ($s){
            $s->whereSchoolId(Auth::user()->school_id);
        })->first();

        $classes = Form::whereHas('allocation',function ($s) use ($term){
            $s->whereTermId($term->id??0);
        })->class()->get();

        if (\request()->a) return $classes;

        return inertia('Examinations/GradeBook',compact('term','classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $allocation = Allocation::find($request->allocation);
        $systems = Grading::whereType($allocation->form->number>2?'MSCE':'JCE')->get();

        $grades = Grade::whereAllocationId($allocation->id)->get();

        foreach ($grades as $grade) {

            $score = array_sum($grade->scores);

            foreach ($systems as $system) {

                if ($score >= $system->min && $score <= $system->max) {

                    $grade->update([
                        'score'=>$score,
                        'status'=>2,
                        'grading_id'=>$system->id
                    ]);
                    $grade->save();
                }

            }

        }

        $grades = Grade::whereAllocationId($allocation->id)->orderByDesc('score')->get();

        $rank = 1;
        $previous = null;
        foreach ($grades as $score) {
            if ($previous != null && $previous->score != $score->score) $rank++;

            if ($score->score != null) {
                $score->position = $rank;
                $previous = $score;
                $score->save();
            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($form)
    {
        $subjects = Subject::has('schools')->select('id','name')->get();

        $form = Form::find($form);
        $term = Term::status()->whereHas('calendar',function ($s){
            $s->whereSchoolId(Auth::user()->school_id);
        })->first();
       $allocation = Allocation::with(['user'=>function($s){
            $s->simple();
        }])->whereTermId(request()->t)->whereSubjectId(\request()->s)->whereFormId($form->id)->first();

        $papers = Paper::whereSubjectId(\request()->s)->get();
         $status = Grade::whereAllocationId($allocation->id??0)->whereNotNull('scores')->first()->status??0;
        $users = User::simple()->whereHas('grade',function ( $q ) use ( $form,$allocation ) {
            $q->whereAllocationId($allocation->id??0);
        })->with(['grade'=>function ( $q ) use ($allocation, $form ) {
            $q->select('score','user_id','grading_id','scores')
                ->whereAllocationId($allocation->id??0)
                ->with(['grading'=>function ( $q ){
                    $q->select('grade','remark','id');
                }]);
        }])->whereHas('form',function ( $q ) use ( $form ) {
            $q ->whereId($form->id);
        })->student()->active()->orderBy('gender')->orderBy('lname')->paginate(30);
        $users->setPath(\request()->fullUrl());


        return inertia('Examinations/Grades',compact('subjects','users','form','status','papers','term','allocation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $grade)
    {

         Grade::whereAllocationId($grade)

            ->update([
            'score'=>null,
            'grading_id'=>null,
            'position'=>null,
            'status'=>null,
        ]);
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
