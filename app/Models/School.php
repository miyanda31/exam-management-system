<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class School extends Model
{
    //
    protected $guarded=['id'];

    public $timestamps = false;
    protected $casts = [
        'about'=>'array'
    ];

    public function ScopeSchool($query)
    {
        return $query->whereId(Auth::user()->school_id);
    }

    public function user()
    {
       return $this->hasMany(User::class);
    }


    public function subject()
    {
       return $this->belongsToMany(Subject::class);
    }

    public function parents()
    {
       return $this->hasMany(User::class)->whereType('Parent')->active();
    }

    public function students()
    {
       return $this->hasMany(User::class)->whereType('Student')->active();
    }

    public function teachers()
    {
       return $this->hasMany(User::class)->whereType('Teacher')->active();
    }




}
