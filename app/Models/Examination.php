<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsToMany(User::class)->wherePivot('cat','supervisor');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->wherePivot('cat','tod');
    }

}
