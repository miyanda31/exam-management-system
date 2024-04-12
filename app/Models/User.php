<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

//use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
        protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setNewApiToken()
    {
        $this->api_token = Str::uuid();
        $this->save();
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function guardian()
    {
        return $this->belongsToMany(static::class,'wards','ward_id','user_id');
    }


    public function ward()
    {
        return $this->belongsToMany(static::class,'wards','user_id','ward_id');
    }

    public function ScopeSchoolId($query)
    {
        return $query->whereSchoolId(Auth::user()->school_id);
    }
    public function ScopeStatus($query, $status)
    {
        return $query->whereStatus($status);
    }
    public function ScopeActive($query)
    {
        return $query->status(1);
    }
    public function ScopeInActive($query)
    {
	    return $query->status(0);
    }
    public function scopeName($query)
    {
        return $query->select(DB::raw('concat(left(fname,1),". ",lname) as name'),'id');
    }
    public function scopeFullname($query)
    {
        return $query->select(DB::raw('concat(lname," ",fname) as name'),'id');
    }
    public function scopeSimple($query)
    {
        return $query->select('id',DB::raw('concat(lname," ",fname) as name'),'username',DB::raw('gender'),'student_id');
    }
    public function ScopeMale($query)
    {
        return $query->whereGender('male');
    }
    public function ScopeFemale($query)
    {
        return $query->whereGender('female');
    }
    public function ScopeAdmin($query)
    {
        return $query->whereType('Admin');
    }
    public function ScopeType($query,$type)
    {
        return $query->whereType($type);
    }
    public function ScopeSearch($query,$name)
    {
        return $query->where(function ($q) use ($name) {
        $q->where('fname','like','%'.$name.'%')
            ->orWhere('lname','like','%'.$name.'%');
    });
    }

    public function ScopeParentOrTeacher($query)
    {
        return $query->where(function ($q)  {
        $q->where('type','parent')
            ->orWhere('type','teacher');
    });
    }
    public function ScopeStudentOrTeacher($query)
    {
        return $query->where(function ($q)  {
        $q->where('type','Student')
            ->orWhere('type','teacher');
    });
    }
    public function ScopeAdminOrTeacher($query)
    {
        return $query->where(function ($q)  {
        $q->where('type','Admin')
            ->orWhere('type','Teacher');
        });
    }


    public function ScopeParent($query)
    {
        return $query->whereType('parent');
    }
    public function ScopeSelectable($query)
    {
        return $query->select('role_id',DB::raw('concat(fname," ",lname) as name'),'gender','id','avatar','fname','lname','phone');
    }
    public function ScopeProfile($query)
    {
        return $query->select(DB::raw('concat(lname," ",fname) as name'),'avatar','type','username','id','gender','fname','lname');
    }

    public function ScopeInitials($query)
    {
        return $query->select('id',DB::raw('concat(left(fname,1),". ",lname) as name'),DB::raw('left(gender,1) as gender'),'avatar','fname','username');
    }

    public function ScopeTeacher($query)
    {
        return $query->whereType('Teacher');
    }

    public function ScopeStudent($query)
    {
        return $query->whereType('Student');
    }

    public function code()
    {
        return $this->hasOne(Code::class);
    }




    public function subject()
    {
        return $this->belongsToMany(Subject::class);
    }


    public function allocation()
    {
        return $this->hasMany(Allocation::class);
    }

    public function form()
    {
        return $this->belongsToMany(Form::class)->withPivot('calendar_id')->wherePivot('calendar_id',$this->calendar()->id);
        //
    }

    public function calendar()
    {
       $calendar = Calendar::whereHas('term',function ($q){
            $q->status();
        })->first();
        return $calendar;
    }


    public function grade()
    {
        return $this->hasOne(Grade::class);
    }
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function score()
    {
        return $this->hasOne(Score::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function recently()
    {
        return $this->hasOne(Payment::class)->latest();
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function bursary()
    {
        return $this->belongsToMany(Bursary::class);
    }


    public function scopeUsername($query,$username)
    {
        return $query->whereUsername($username);
    }



}
