<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    //
    protected $guarded = ['id'];
    public $timestamps  = false;

    public function term()
    {
        return $this->hasMany(Term::class);
    }

}
