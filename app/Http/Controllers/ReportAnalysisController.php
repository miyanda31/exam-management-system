<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        if (\request()->c) {
            $grades = Grade::whereHas('allocation',function ($s){
                $s->whereTermId(\request()->t)
                    ->whereFormId(\request()->f);
            })

                ->select(DB::raw('round(avg(score),0) as avg'),'allocation_id')
                ->withCount(['allocation as subject'=>function($s) {
                    $s->withCount(['subject'=>function($s){
                        $s->select('short_code');
                    }])->select('subject_id');
                }])
                ->groupBy('allocation_id')

                ->get();

            $subjects = $grades->map(function ($s){
                return Subject::find($s->subject)->short_code;
            });

            $data = $grades->map(function ($s){
                return $s->avg;
            });

            $data = [
                'name'=>'Average Score',
                'data'=>$data
            ];

            $average= Grade::whereHas('allocation',function ($s){
                $s->whereTermId(\request()->t)
                    ->whereFormId(\request()->f);
            })

                ->select(DB::raw('round(avg(score),0) as avg'))
                ->first()->avg;
            return response()->json(['data'=>$data,'subjects'=>$subjects,'average'=>$average]);
        }

        if (\request()->p) {

            $term1 = Term::select('number')->find(\request()->t1);
            $term2 = Term::select('number')->find(\request()->t2);

            $first = Grade::whereHas('allocation',function ($s){
                $s->whereTermId(\request()->t1)
                    ->whereFormId(\request()->f);
            })

                ->select(DB::raw('round(avg(score),0) as avg'),'allocation_id')
                ->withCount(['allocation as subject'=>function($s) {
                    $s->withCount(['subject'=>function($s){
                        $s->select('short_code');
                    }])->select('subject_id');
                }])
                ->groupBy('allocation_id')

                ->get();;
            $second = Grade::whereHas('allocation',function ($s){
                $s->whereTermId(\request()->t2)
                    ->whereFormId(\request()->f);
            })

                ->select(DB::raw('round(avg(score),0) as avg'),'allocation_id')
                ->withCount(['allocation as subject'=>function($s) {
                    $s->withCount(['subject'=>function($s){
                        $s->select('short_code');
                    }])->select('subject_id');
                }])
                ->groupBy('allocation_id')

                ->get();;

            $subjects = $second->map(function ($s){
                return Subject::find($s->subject)->short_code;
            });

            $data1 = $first->map(function ($s){
                return $s->avg;
            });

            $data2 = $second->map(function ($s){
                return $s->avg;
            });


            $average= round($data1->average());
            $average2= round($data2->average());

            $data1 = [
                'name'=>'Term '.$term1->number,
                'data'=>$data1
            ];

            $data2 = [
                'name'=>'Term '.$term2->number,
                'data'=>$data2
            ];

            return response()->json(['data'=>[$data1,$data2],'subjects'=>$subjects,'average'=>[$average,$average2]]);
        }

        if (\request()->s) {
            $term1 = Term::select('number')->find(\request()->t1);
            $term2 = Term::select('number')->find(\request()->t2);
            $first = Grade::whereHas('allocation',function ($s){
                $s->whereTermId(\request()->t1);
            })
                ->whereUserId(\request()->u)

                ->select('score')
                ->withCount(['allocation as subject'=>function($s) {
                    $s->withCount(['subject'=>function($s){
                        $s->select('short_code');
                    }])->select('subject_id');
                }])

                ->get();
            $second = Grade::whereHas('allocation',function ($s){
                $s->whereTermId(\request()->t2);
            })
                ->whereUserId(\request()->u)
                ->select('score')
                ->withCount(['allocation as subject'=>function($s) {
                    $s->withCount(['subject'=>function($s){
                        $s->select('short_code');
                    }])->select('subject_id');
                }])

                ->get();

            $subjects = $second->map(function ($s){
                return Subject::find($s->subject)->short_code;
            });

            $data1 = $first->map(function ($s){
                return $s->score;
            });

            $data2 = $second->map(function ($s){
                return $s->score;
            });

           $average= round($data1->average());
           $average2= round($data2->average());


            $data1 = [
                'name'=>'Term '.$term1->number,
                'data'=>$data1
            ];

            $data2 = [
                'name'=>'Term '.$term2->number,
                'data'=>$data2
            ];
            return response()->json(['data'=>[$data1,$data2],'subjects'=>$subjects,'average'=>[$average,$average2]]);
        }

        $classes = Form::class()->get();
        $terms = Term::where('status','>=','1')->withCount(['calendar as year'=>function($s){
            $s->select('academic');
        }])->get();

        return inertia('Examinations/Analysis',compact('classes','terms'));
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($grade)
    {
        $users = User::search($grade)->fullname()->with(['scores'=>function($s){
            $s->select('term_id','user_id')->with(['term'=>function($s){
               $s->select('id','number','calendar_id')
                   ->withCount(['calendar as year'=>function($s){
                   $s->select('academic');
               }]);
            }]);
        }])->student()->active()->get();
       return response()->json($users);
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
