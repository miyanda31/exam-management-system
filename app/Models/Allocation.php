<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function grade()
    {
        return $this->hasMany(Grade::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class,'role_id','id');
    }

    public function form()
    {
        return $this->hasOne(Form::class,"id","form_id");
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    public function timetable()
    {
        return $this->hasMany(Timetable::class);
    }
    public function loads()
    {
        return $this->hasMany(Timetable::class,'user_id','user_id');
    }

    public function scopeUser($query)
    {
        return $query->whereUserId(Auth::id());
    }
}
