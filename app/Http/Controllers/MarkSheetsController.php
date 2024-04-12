<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Grade;
use App\Models\Score;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarkSheetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $term = Term::status()->whereHas('calendar',function ($s){
            $s->whereSchoolId(Auth::user()->school_id);
        })->first();

        $classes = Form::whereHas('allocation',function ($s) use ($term){
            $s->whereTermId($term->id??0)->whereHas('grade',function ($s) use ($term){
                $s->whereStatus(2);
            });
        })->get();

        if (\request()->a) return response()->json(compact('term','classes'));

        return inertia('Examinations/MarkSheets',compact('term','classes'));
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
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($grade)
    {


       $terms = Term::withCount(['calendar as year'=>function($s) {
            $s->select('academic');
        }])->whereHas('calendar',function ($s){
           $s->whereSchoolId(Auth::user()->school_id);
       })->get();

       $term = Term::find(\request()->t);

       $classes = Form::class()->whereSchoolId(Auth::user()->school_id)->get();
       $subjects = Subject::has('schools')->get(['id','short_code']);

       $form = Form::find($grade);

      $users = User::simple()->with(['grades'=>function ( $q ) use ( $grade ) {
            $q->select('score','user_id','grading_id')->whereHas('allocation',function ($s) use ($grade){
                $s->whereTermId(request()->t)->whereFormId($grade);
            })->withCount(['grading as grade'=>function ( $q ){
                $q->select('grade');
            }])->withCount(['allocation as subject'=>function ( $q ){
                $q->select('subject_id');
            }]);
        }])->whereHas('form',function ( $q ) use ( $grade ) {
            $q ->whereId($grade);
        })->student()->active()->orderBy('gender')->orderBy('lname')->paginate(30);


        $status = Score::whereTermId(request()->t)->whereHas('grade',function ( $q ) use ( $grade ) {
            $q->whereFormId($grade)->whereTermId(request()->t);
        })->count();



        $users->setPath(\request()->fullUrl());

        return inertia('Examinations/MarkSheet',compact('users','status','terms','classes','form','subjects','term'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $grade)
    {
        $form = Form::find($grade);
        $term =Term:: find(request()->t);

        $aggregator = Subject::has('schools')->whereName("english")->first();



//       TODO:  calculate position in class
       $users =  User::select('id')->whereHas('form',function ($query) use ($grade) {
            $query->whereId($grade);
        })->withCount(['grade as total'=>function($q) use ($term, $grade) {
            $q->whereHas('allocation',function ($s) use ($grade){
                $s->whereTermId(request()->t)->whereFormId($grade);
            })->select(DB::raw('sum(score)'));
        }])->withCount(['grade as eng'=>function($q) use ($aggregator, $term, $grade) {
            $q->whereHas('allocation',function ($s) use ($aggregator, $grade){
                $s->whereTermId(request()->t)->whereFormId($grade)->where('subject_id','=',$aggregator->id);
            })->where('score','>=',40);
        }])->withCount(['grade as others'=>function($q) use ($aggregator, $term, $grade) {
            $q->whereHas('allocation',function ($s) use ($aggregator, $grade){
                $s->whereTermId(request()->t)->whereFormId($grade)->where('subject_id','<>',$aggregator->id);
            })->where('score','>=',40);
        }])->student()->active()->orderByDesc('total')->get();

        foreach ($users as $user) {

            $english = Grade::whereHas('allocation',function ($s) use ($aggregator, $term, $form){
                $s->whereSubjectId($aggregator->id)->whereFormId($form->id)->whereTermId($term->id);
            })->whereUserId($user->id)->first();

             $written = Grade::whereHas('allocation',function ($s) use ($aggregator, $term, $form){
                 $s->where('subject_id', '<>',$aggregator->id)->whereFormId($form->id)->whereTermId($term->id);
             })->whereUserId($user->id)->withCount(['grading as grade'=>function($s){
                 $s->select('grade');
             }])->where('grading_id', '>',$form->number > 2?1:17)->orderByDesc('score')->take(5)->get();

            $avg = $form->number > 2 ? ($written->sum('grade') + $english ? $english->grading->grade : 0) : ($english->score + $written->sum('score')) / 6;

            $score = Score::whereUserId($user->id)->whereTermId($term->id)->first();

               if ($score) {
                   $score->update([
                       'aggregate'=>$avg,
                       'eng'=>$user->eng,
                       'passed'=>$user->eng+$user->others,
                   ]);
               }
               else {
                   Score::updateOrCreate([
                       'aggregate'=>$avg,
                       'user_id'=>$user->id,
                       'term_id'=>$term->id,
                       'form_id'=>$form->id,
                       'score'=>$user->total,
                       'eng'=>$user->eng,
                       'passed'=>$user->eng+$user->others,
                       'school_id'=>Auth::user()->school_id
                   ]);
               }

        }


    // TODO: class positioning

        $sort = $form->number>2?'ASC':'DESC';

            $rankings = DB::raw('FIND_IN_SET( aggregate ,
        (SELECT GROUP_CONCAT(aggregate ORDER BY aggregate '.$sort.')  FROM scores a where eng = 1 and passed>5 and form_id='.$form->id.' and term_id='.$term->id.' and a.school_id='.Auth::user()->school_id.'))
        position');

         $positions = Score::whereTermId($term->id)->select('id','aggregate',$rankings)->whereFormId($form->id)->where('passed','>',5)->where('eng',1)->get();


        foreach ($positions as $position) {
            Score::find($position->id)->update([
                'class_position' => $position->position,
                'remark'=>'Has satisfactorily passed'
            ]);
        }

        // Has failed key subject

        $last  = Score::whereTermId($term->id)->whereFormId($form->id)->where('eng',1)->where('passed','>',5)->orderbyDesc('class_position')->first();

        $total =Score::whereTermId($term->id)->whereClassPosition($last->class_position)->whereFormId($form->id)->count()+$last->class_position-1;

        $rankings = DB::raw('FIND_IN_SET( aggregate ,
        (SELECT GROUP_CONCAT(aggregate ORDER BY aggregate '.$sort.')  FROM scores a where eng = 0 and passed>5 and form_id='.$form->id.' and term_id='.$term->id.' and a.school_id='.Auth::user()->school_id.'))
        position');

        $positions = Score::whereTermId($term->id)->select('id','aggregate',$rankings)->whereFormId($form->id)->where('passed','>',5)->where('eng',0)->get();


        foreach ($positions as $position) {
            Score::find($position->id)->update([
                'class_position' => $position->position+$total,
                'remark'=>'Has failed key subject'
            ]);
        }

        // Has completely failed
        $last  = Score::where('passed','>',5)->where('eng',0)->whereTermId($term->id)->whereFormId($form->id)->orderbyDesc('class_position')->first();

        $total =Score::whereTermId($term->id)->whereClassPosition($last->class_position)->whereFormId($form->id)->count()+$last->class_position-1;

        $rankings = DB::raw('FIND_IN_SET( aggregate ,
        (SELECT GROUP_CONCAT(aggregate ORDER BY aggregate '.$sort.')  FROM scores a where passed<6 and form_id='.$form->id.' and term_id='.$term->id.' and a.school_id='.Auth::user()->school_id.' ))
        position');

        $positions = Score::whereTermId($term->id)->select('id','aggregate',$rankings)->whereFormId($form->id)->where('passed','<',6)->get();


        foreach ($positions as $position) {
            Score::find($position->id)->update([
                'class_position' => $position->position+$total,
                'remark'=>'Has failed'
            ]);
        }

       // TODO: position according to stream

        $sort = $form->number>2?'ASC':'DESC';
        $rankings = DB::raw('FIND_IN_SET( aggregate ,
        (SELECT GROUP_CONCAT(aggregate ORDER BY aggregate '.$sort.')  FROM scores a inner join forms b on a.form_id = b.id where eng = 1 and passed>5  and b.number='.$form->number.' and a.term_id='.$term->id.' and a.school_id='.Auth::user()->school_id.'))
        position');

        $positions = Score::whereTermId($term->id)->select('id','aggregate',$rankings)->whereHas('form',function ($q) use ( $form ) {
            $q->whereNumber($form->number);
        })->has('grades')->where('passed','>',5)->where('eng',1)->get();


        foreach ($positions as $position) {
            Score::find($position->id)->update([
                'position' => $position->position,
                'status'=>1
            ]);
        }

        // Has failed key subject

        $last  = Score::whereTermId($term->id)->where('passed','>',5)->where('eng',1)->whereHas('form',function ($q) use ( $form ) {
            $q->whereNumber($form->number);
        })->orderbyDesc('position')->first();

        $total = Score::wherePosition($last->position)->count()+$last->position-1;

        $rankings = DB::raw('FIND_IN_SET( aggregate ,
        (SELECT GROUP_CONCAT(aggregate ORDER BY aggregate '.$sort.')  FROM scores a inner join forms b on a.form_id = b.id where eng = 0 and passed>5 and b.number='.$form->number.' and term_id='.$term->id.' and a.school_id='.Auth::user()->school_id.'))
        position');

        $positions = Score::whereTermId($term->id)->select('id','aggregate',$rankings)->whereHas('form',function ($q) use ( $form ) {
            $q->whereNumber($form->number);
        })->where('passed','>',5)->where('eng',0)->orderByDesc('aggregate')->get();


        foreach ($positions as $position) {
            Score::find($position->id)->update([
                'position' => $position->position+$total,
                'status'=>1
            ]);
        }

        // Has completely failed
        $last  = Score::whereTermId($term->id)->where('passed','>',5)->where('eng',0)->whereTermId($term->id)->whereHas('form',function ($q) use ( $form ) {
            $q->whereNumber($form->number);
        })->orderbyDesc('position')->first();

        $total = Score::wherePosition($last->position)->count()+$last->position-1;

        $rankings = DB::raw('FIND_IN_SET( aggregate ,
        (SELECT GROUP_CONCAT(aggregate ORDER BY aggregate '.$sort.')  FROM scores a inner join forms b  on a.form_id = b.id where passed<6  and b.number='.$form->number.' and term_id='.$term->id.' and a.school_id='.Auth::user()->school_id.' ))
        position');

        $positions = Score::whereTermId($term->id)->select('id','aggregate',$rankings)->whereHas('form',function ($q) use ( $form ) {
            $q->whereNumber($form->number);
        })->where('passed','<',6)->orderByDesc('aggregate')->get();


        foreach ($positions as $position) {
            Score::find($position->id)->update([
                'position' => $position->position+$total,
                'status'=>1
            ]);
        }


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
