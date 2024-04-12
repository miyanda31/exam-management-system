<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Department extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;


    public function user() {
        return $this->belongsToMany(User::class);
    }
}
