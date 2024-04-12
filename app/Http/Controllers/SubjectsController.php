<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    public function index()
    {
       $subjects = Subject::doesntHave('schools')->get(['name','id','code','short_code']);

        $offered =Subject::has('schools')->get(['name','id','code','short_code']);
        return response()->json([$subjects,$offered],200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $school = School::whereHas('user',function($s){
            $s->whereId(Auth::id());
        })->first();

         $school->subject()->attach($request->subject);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(School $subject)
    {

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, School $subject)
    {
        $messages =[
            'required'=>'No subject has been selected'
        ];
        $this->validate($request,[
            'subject'=>'required'
        ],$messages);

        $subject->subject()->sync($request->subject);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $school = School::whereHas('user',function($s){
            $s->whereId(Auth::id());
        })->first();

        $school->subject()->detach($id);
    }
}
