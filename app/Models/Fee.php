<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Fee extends Model
{
    //
    protected $guarded=['id'];

    public function package()
    {
        return $this->belongsToMany(Package::class);
    }
    public function form()
    {
        return $this->belongsToMany(Form::class);
    }
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

}
