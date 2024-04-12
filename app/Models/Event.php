<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    //
    protected $guarded = ['id'];


    public function ScopeType($query,$type)
    {
        return $query->whereType($type);
    }
    public function ScopeStatus($query,$type)
    {
        return $query->whereStatus($type);
    }
    public function ScopeOrigin($query,$origin)
    {
        return $query->whereOrigin($origin);
    }
    public function ScopeNew($query)
    {
        return $query->whereStatus(0);
    }

    public function ScopeColumns($query)
    {
        return $query->select(DB::raw('concat(title," ",form_id) as title'),'start','end','color');
    }

    public function tasks()
    {
        return $this->belongsToMany(Department::class)->wherePivot('type','Task');
    }
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    public function activities()
    {
        return $this->belongsToMany(Department::class)->wherePivot('type','Event');
    }
    public function club()
    {
        return $this->belongsToMany(Club::class);
    }
    public function department()
    {
        return $this->belongsToMany(Department::class);
    }
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->wherePivot('cat','tod');
    }

    public function ScopeEvents($query)
    {
        $start = Carbon::now()->subDays(30);
        $end = Carbon::now()->addDays(30);
        return $query->whereBetween('start',[$start,$end]);
    }
}
