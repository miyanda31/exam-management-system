<?php

namespace App\Http\Controllers;

use App\Models\Grading;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{


    public function validation()
    {
        $this->validate(\request(),[
            'name'=>'required|min:4|bail|string',
            'email'=>'required|email|bail|string',
            'motto'=>'required|min:4|bail|string',
            'phone'=>'required|min:4|bail|string',
            'address'=>'required|min:4|bail|string',
            'photo'=>'image',

        ],[
            'required'=>'Field is required'
        ]);
    }

    public function index()
    {
        $school = Auth::user()->school;
        return response()->json($school,200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validation();

        $school = School::updateOrCreate([
            'name'=>$request->name,
            'email'=>$request->email,
            'motto'=>$request->motto,
            'phone'=>$request->phone,
            'address'=>$request->address
        ]);

        $grading = Grading::whereSchoolId(null)->get();

         Grading::updateOrCreate([
                'school_id'=>$school->id,
                'max'=>$grading->max,
                'min'=>$grading->min,
                'grade'=>$grading->grade,
                'remark'=>$grading->remark,
                'type'=>$grading->type,
         ]);

        if ($request->hasFile('logo')) {

            $name = $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('img'),$name);

           $school->update([
               'logo'=>$name
           ]);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($school)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $school)
    {
        $this->validation();

        $school = School::find($school);

        $school->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'motto'=>$request->motto,
            'phone'=>$request->phone,
            'address'=>$request->address,
        ]);


        if ($request->hasFile('logo')) {

            $file = public_path('img').$school->logo ;
            if (file_exists($file)) unlink($file);

            $name = $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('img'),$name);

            $school->update([
                'logo'=>$name
            ]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
