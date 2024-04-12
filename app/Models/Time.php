<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Time extends Model
{
    //
    protected $guarded = ['id'];

    public $timestamps = false;

    public function timetable()
    {
        return $this->hasMany(Timetable::class);
    }

    public function ScopeSchool($query)
    {
        return $query->whereSchoolId(Auth::user()->school_id);
    }

    public function scopeShift($query,$shift)
    {
        return $query->whereShift($shift);
    }

    public function ScopeStart($query) {
    	return $query->where('start','>=',date('H:i:s'))->orWhere('altstart','>=',date('H:i:s'));
    }

}
