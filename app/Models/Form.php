<?php

namespace App\Models;

use App\Models\Setting;
use App\Models\Timetable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Form extends Model
{
    //
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsToMany(User::class)->withPivot('calendar_id')->wherePivot('calendar_id',$this->calendar()->id??0);
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function grade()
    {
        return $this->hasMany(Grade::class);
    }
    public function allocation()
    {
        return $this->hasMany(Allocation::class);
    }

    public function calendar()
    {
        return Calendar::whereHas('term',function ($q){
            $q->status();
        })->first();
    }
    public function subject()
    {
        return $this->belongsToMany(Subject::class);
    }
    public function fee()
    {
        return $this->belongsToMany(Fee::class);
    }
    public function monitor()
    {
        return $this->belongsTo(User::class,'monitor_id');
    }
    public function monitress()
    {
        return $this->belongsTo(User::class,'monitress_id');
    }
    public function setting()
    {
        return $this->belongsToMany(Setting::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    public function timetable()
    {
        return $this->hasMany(Timetable::class);
    }

    public function ScopeTerm($query,$term)
    {
        return $query->whereTermId($term);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function ScopeClass($query)
    {
        return $query->select(DB::raw('concat(number,name) as name'),'id','shift');
    }


}
