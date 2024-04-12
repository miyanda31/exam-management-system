<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromoteStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
             $users = User::select(DB::raw('concat(fname," ",lname) as name'),'id')->whereHas('form',function ( $q ){
                $q->whereId(\request()->f);
            })->search(\request()->q)->schoolId()->student()->active()->get();

             return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = Form::find($request->id);

        $new = Form::whereNumber($form->number + 1)->whereName($form->name)->whereSchoolId(Auth::user()->school_id)->first();

        if ($new) {
            $users = User::student()->active();
            if ($request->t == 's') {

                $users = $users->whereHas('form',function ( $q ) use ( $request ) {
                    $q->whereId($request->id);
                })->whereNotIn('id',$request->users);

            }
            else {
                $users = $users->whereHas('form',function ( $q ) use ( $request ) {
                    $q->whereId($request->id);
                });
            }

            $new->user()->syncWithoutDetaching($users->pluck('id')->toArray(),['calendar_id'=>$request->calendar]);

        }
        else {
            User::whereHas('form',function ( $q ){
                $q->whereId(\request()->id);
            })->update([
                'status'=>0
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Code $code)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy(Code $code)
    {
        //
    }
}
