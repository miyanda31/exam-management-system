<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use function foo\func;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        if (\request()->s) {
            return Form::class()->get();
        }
        return Form::withCount(['user as students'=>function($q){
            $q->student()->status(1);
        }])->paginate();
    }

    public function validation($request)
    {
        $this->validate($request,[
            'category'=>'required',
            'shift'=>'required',

        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);
		 $forms = Form::get();

        if (explode(',',$request->category) && $shift = explode(',',$request->shift)) {
	        if ($forms->count()>0) {
		        Form::truncate();
	        }
	        $category = explode(',',$request->category);

	        for ($i = 1; $i <= $request->number ; $i++) {
		        foreach ($category as $item) {
			        foreach ($shift as $sh) {

				        Form::firstOrCreate([
					        'number'=>$i,
					        'shift'=>$sh,
					        'name'=>$item,
				        ]);
			        }
		        }
	        }
        }
        else {
        	return response()->json(['name'=>'Please separate streams and class by a comma'],422);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormMaker  $form
     * @return \Illuminate\Http\Response
     */
    public function show($form)
    {
        return Form::find($form);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormMaker  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $class)
    {
        $this->validation($request);

        Form::whereShift($class)->delete();

        $category = explode(',',$request->category);
        $shift = explode(',',$request->shift);

        for ($i = 1; $i <= $request->classes ; $i++) {
            foreach ($category as $item) {
                foreach ($shift as $sh) {

                    Form::firstOrCreate([
                        'number'=>$i,
                        'shift'=>$sh,
                        'name'=>$item,
                    ]);
                }
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormMaker  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy($form)
    {
        //
    }
}
