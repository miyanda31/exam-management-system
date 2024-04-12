<?php

namespace App\Http\Controllers;

use App\Models\Grading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradingController extends Controller
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
     * @param  String $grading
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function show(String $grading)
    {
        //TODO: insert grading system for the school
//        $gradings = Grading::whereNull('school_id')->orderBy('grade')->get();
//
//        foreach ($gradings as $item) {
//            Grading::updateOrCreate([
//                'school_id'=>1,
//                'max'=>$item->max,
//                'min'=>$item->min,
//                'grade'=>$item->grade,
//                'remark'=>$item->remark,
//                'type'=>$item->type,
//            ]);
//        }
        $gradings = Grading::whereSchoolId(Auth::user()->school_id)->whereType($grading)->orderBy('grade')->get();

      if (\request()->a)  return response()->json(compact('gradings'));

        return inertia('Settings/GradingSystem',compact('gradings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grading  $grading
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grading $grading)
    {
        $grading->update([
            $request->t=>$request->v
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grading  $grading
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grading $grading)
    {
        //
    }
}
