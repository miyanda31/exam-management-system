<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Timetable extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function ScopeSchool($query)
    {
        return $query->whereSchoolId(Auth::user()->school_id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function register()
    {
        return $this->hasOne(Register::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function time()
    {
        return $this->belongsTo(Time::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

}
