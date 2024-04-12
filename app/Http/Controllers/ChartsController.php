<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Form;
use App\Models\Payment;
use App\Models\Term;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        $term = Term::whereNotNull('id')->whereHas('calendar',function ($s){
            $s->whereSchoolId(\request()->user()->school_id);
        })->status()->first();



        if (\request()->r) {

            $classes = Form::class()->whereHas('user',function ($s) use ($term) {
                $s->whereCalendarId($term->calendar_id);
            })->whereSchoolId(\request()->user()->school_id)->get();

            $forms = $classes->map(function ($data){
                return $data->name;
            });

            $males = [];
            $females = [];
            $maleTotals = 0;
            $femaleTotals = 0;
            foreach ( ['Male','Female'] as $gender ) {

                foreach ( $classes as $class ) {

                    $users = User::whereGender($gender)->whereHas('form',function ($q) use ( $class ) {
                        $q->whereId($class->id);
                    });

                    $users = $users->student()->active()->count();

                    $gender == "Male"? $maleTotals += $users : $femaleTotals += $users;
                    $gender == "Male"? $males[] = $users : $females[] = $users;
                }

            }

            return response()->json(['malesTotal'=>$maleTotals,'femalesTotal'=>$femaleTotals,'males'=>$males,'females'=>$females,'classes'=>$forms]);

        }


        if (\request()->a) {
            $males = [];
            $females = [];
            $classes = [1,2,3,4];
            $maleTotals = 0;
            $femaleTotals = 0;
            foreach ( ['Male','Female'] as $gender ) {

                foreach ( $classes as $class ) {

                    $users = User::whereGender($gender)->whereHas('form',function ($q) use ( $class ) {
                        $q->whereNumber($class);
                    });

                    $users = $users->student()->active()->count();

                    $gender == "Male"? $maleTotals += $users : $femaleTotals += $users;
                    $gender == "Male"? $males[] = $users : $females[] = $users;
                }

            }

           return response()->json(['malesTotal'=>$maleTotals,'femalesTotal'=>$femaleTotals,'males'=>$males,'females'=>$females,'classes'=>$classes]);


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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
