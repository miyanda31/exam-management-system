<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        if (Auth::guest()) return view('auth.login');

        $icons = [
            'Admin'=>'dw-user-13',
            'Student'=>'dw-add-user',
            'Teacher'=>'dw-user-1',
            ];

        $colors = [
            'Admin'=>'#00eccf',
            'Student'=>'#ff5b5b',
            'Term'=>'',
            'Teacher'=>'#09cc06',
            ];

        $users = User::groupBy('type')
            ->select(DB::raw('count(*) as total'),'type')
            ->orderBy('type')
            ->whereSchoolId(Auth::user()->school_id)
            ->get()
            ->map(function ($data) use ($colors, $icons) {
            return [
                'type'=>$data->type,
                'total'=>$data->total,
                'icon'=>$icons[$data->type],
                'color'=>$colors[$data->type],
            ];
        });


       $term = Term::status()->whereHas('calendar',function ($s){
           $s->whereSchoolId(Auth::user()->school_id);
       })->select('id','number','calendar_id')->first();


      if(\request()->a) return response()->json(compact('users','term'));

        $user = User::fullname()->find(Auth::id());
        return Auth::user()->type === 'Admin' ? inertia('Dashboard',compact('users','user','term')):$this->loadTeacherData();
    }

    private function loadTeacherData()
    {
        return redirect()->to(route('data-entry.index'));
    }


}
