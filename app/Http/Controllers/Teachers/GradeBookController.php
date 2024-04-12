<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
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
        $term = Term::status()->first();

       $allocations = Allocation::with('subject:id,name','form:id,name,number')->whereUserId(Auth::id())->whereTermId($term->id??0)->get();

        return inertia(Auth::user()->type=='Admin'?'Staff/DataBook':'Staff/GradeBook',compact('term','allocations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    $grade = Grade::whereUserId($request->u)->whereAllocationId($request->a)->first();
        if ($grade) {
            $grade->scores = array_merge($grade->scores,[$request->c=>$request->v]);
            $grade->save();

        } else {
           $grade = Grade::updateOrCreate([
                'allocation_id'=>$request->a,
                'user_id'=>$request->u,
                'scores'=>[$request->c=>$request->v],
            ]);

        }

      return $grade;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($form)
    {


        $term = Term::status()->first();


        if (\request()->a) {
            $allocation = Allocation::find($form);

            $users = User::simple()->with(['grade'=>function ( $q ) use ( $form ) {
                $q->select('score','user_id','grading_id','scores')
                   ->whereAllocationId($form)
                    ->with(['grading'=>function ( $q ){
                        $q->select('grade','remark','id');
                    }]);
            }])->whereHas('form',function ( $q ) use ( $allocation ) {
                $q ->whereId($allocation->form_id);
            })->student()->active()->orderBy('gender')->orderBy('lname')->paginate(30);
            $users->setPath(\request()->fullUrl());

            if (\request()->page) return $users;

            $papers = Paper::whereSubjectId($allocation->subject_id)->get();
            $status = Grade::whereAllocationId($form)->first()->status??0;

            return response()->json(compact('users','status','papers','term')) ;
        }

        $allocation = Allocation::whereFormId($form)->whereTermId(\request()->t)->whereSubjectId(\request()->s)->first();

       $form = Form::with(['users'=>function($s){
            $s->simple();
        }])->find($form);

        $subject = Subject::subject()->find(\request()->s);

        $papers = Paper::whereSubjectId(\request()->s)->get();
       $status = Grade::whereAllocationId($allocation->id)->whereNotNull('scores')->first()->status??0;
       $users = User::simple()->with(['grade'=>function ( $q ) use ( $allocation ) {
            $q->select('score','user_id','grading_id','scores')
                ->whereAllocationId($allocation->id)
                ->with(['grading'=>function ( $q ){
                $q->select('grade','remark','id');
            }]);
        }])->whereHas('form',function ( $q ) use ( $form ) {
            $q ->whereId($form->id);
        })->student()->active()->orderBy('gender')->orderBy('lname')->paginate(30);
        $users->setPath(\request()->fullUrl());



        return inertia(Auth::user()->type=='Admin'?'Staff/DataEntry':'Staff/Grades',compact('users','form','status','papers','term','subject','allocation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $grade)
    {

         Grade::whereAllocationId($grade)

            ->update([
                'status'=>1,
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
