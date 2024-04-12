<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Form;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenerateController extends Controller
{
    protected function validation(Request $request) {
        $rules =[
            'amount'=>'sometimes|required',
            'name'=>'sometimes|required',
        ];
        $messages =[
            'required'=>'This field is required'
        ];
        $this->validate($request,$rules,$messages);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        $fees = Fee::with('package:id,name')->withCount('form')->withCount(['package as total'=>function( $q ){
            $q->select(DB::raw('sum(amount)'));
        }])->withCount(['package as default'=>function( $q ){
            $q->whereNotNull('selected');
        }])->with(['form'=>function( $q ){
            $q->class();
        }])->paginate(15);

        $classes = Form::select(DB::raw('concat(number,name) as name'),'id')->get();
        $packages = Package::select('id','name')->get();

        return inertia('Fees/GenerateFees',compact('packages','fees','classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);

        $fees = Fee::updateOrCreate([
            'name'=> $request->name,
            'description'=> $request->description,
            'groups'=> $request->groups,
        ]);

        $fees->package()->attach($request->packages);

        if ($request->groups === 1) {
            $fees->form()->attach($request->classes);
        }




    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fee $fees
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fee $fees)
    {
        $this->validation($request);

        $fees->update([
            'name'=> $request->name,
            'description'=> $request->description,
        ]);


        $fees->package()->sync($request->packages);

        if ($request->groups === 1) {
            $fees->form()->sync($request->classes);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        //
    }
}
