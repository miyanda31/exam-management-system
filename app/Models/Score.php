<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Score extends Model
{
    //
    public $timestamps = false;

    protected $guarded =['id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function ScopeSchool($query)
    {
        return $query->whereSchoolId(Auth::user()->school_id);
    }

    public function grades()
    {
        return $this->belongsTo(Grade::class,'user_id','user_id');
    }
    public function grade()
    {
        return $this->hasMany(Grade::class,'user_id','user_id');
    }
}
