<?php

namespace App\Models;

use App\Models\Fee;
use App\Models\Grades;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    //
    public $timestamps = false;

    protected $guarded =['id'];

    public function grades()
    {
        return $this->hasMany(Grades::class);
    }
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function fee()
    {
        return $this->hasMany(Fee::class);
    }

    public function ScopeTerms($query)
    {
        return $query->where('status','<>',2);
    }

    public function ScopeStatus($query)
    {
        return $query->whereStatus(1);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
