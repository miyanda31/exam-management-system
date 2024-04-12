<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Code;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{

     public function authUser(Request $request )
    {


        $this->validate($request,[
            'username'=>'required',
            'password'=>'required',
        ],[
            'required'=>"Field is required",
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Authentication passed...
            $user = Auth::id();

            if (Auth::user()->type == 'Student') return response('Unauthorized',401);

            return  $this->saveUser(User::find($user));

        }
        return response('Username or Password is wrong',423);

    }

    public function regUser(Request $request )
    {

         $rules =[
            'exists'=>'Code does not match any in our records',
            'required'=>'This is a required field',
            'confirmed'=>'Passwords do not match',
        ];

        $this->validate($request, [
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'code'=>'required|exists:codes,code',

        ],$rules);

        $code = Code::whereCode($request->code)->first();

           $user = User::find($code->user_id);

           $user->update([
               'username' => $request->username,
               'password' => bcrypt($request->password),
           ]);

           $code->delete();

          return  $this->saveUser($user);
    }

    public function saveUser(User $user)
    {
        $user->api_token = Hash::make(Str::uuid());
        $user->save();

        $allocations = Allocation::withCount(['subject as subject'=>function($s){
            $s->select('name');
        }])->withCount(['form as form'=>function($s){
            $s->select(DB::raw('concat(number,name)'));
        }])->whereUserId(Auth::id())->whereTermId(Term::status()->first()->id??0)->get();

            return response()->json([
                'fname'=> $user->fname,
                'lname'=> $user->lname,
                'username'=> $user->username,
                'id'=> $user->id,
                'type'=>strtolower($user->type),
                'token'=>$user->api_token,
                'allocations'=>$allocations
            ]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
     * @param  int  $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(int $user)
    {
        $user = User::find($user);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
