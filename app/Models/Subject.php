<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subject extends Model
{
    //
    protected $fillable=['name','short_code','maneb_code','exam_fee','school_id'];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function ScopeSchool($query)
    {
        return $query->whereSchoolId(Auth::user()->school_id);
    }

    public function ScopeTerm($query,$term)
    {
        return $query->whereTermId($term);
    }

    public function ScopeSubject($query)
    {
        return $query->select('name','id');
    }
    public function ScopeCodes($query)
    {
        return $query->select('short_code','id');
    }

    public function schools()
    {
        return $this->belongsToMany(School::class);
    }
    public function grade()
    {
        return $this->hasMany(Grade::class);
    }

    public function timetable()
    {
        return $this->hasMany(Timetable::class);
    }

}
