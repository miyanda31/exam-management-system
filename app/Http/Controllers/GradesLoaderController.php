<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;

class GradesLoaderController extends Controller
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
     * @param  int  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(int $grade)
    {
        $allocations = Allocation::whereHas('form',function ( $q ) use ($grade) {
            $q ->whereId($grade);
        })->get();

        $users = User::whereHas('form',function ( $q ) use ($grade) {
            $q ->whereId($grade);
        })->get();

        foreach ($allocations as $allocation) {
            foreach ($users as $user) {
                Grade::updateOrCreate([
                    'allocation_id'=>$allocation->id,
                    'scores'=>[
                        'first'=>random_int(1,20),
                        'second'=>random_int(1,20),
                        'final'=>random_int(1,60),
                    ],
                    'user_id'=>$user->id,
                    'status'=>1
                ]);
            }
        }

        return "done $grade";
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
