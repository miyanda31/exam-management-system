<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    //
    public $timestamps = false;

    protected $guarded = ['id'];
    protected $casts = [
        'scores'=>'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function grading()
    {
        return $this->belongsTo(Grading::class);
    }
    public function ScopeEnglish($query)
    {
        return $query->whereSubjectId(10);
    }
    public function ScopeStatus($query)
    {
        return $query->whereStatus(1);
    }

    public function ScopeTermid($query)
    {
        $term = Term::status()->school()->first();
        return $query->whereTermId($term->id);
    }

    public function ScopeNotEnglish($query)
    {
        return $query->where('subject_id','<>',10);
    }
    public function ScopeSchool($query)
    {
        return $query->whereSchoolId(Auth::user()->school_id);
    }


    public function score()
    {
        return $this->belongsTo(Score::class,'user_id','user_id');
    }
}
