<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    //
    protected $guarded=['id'];

    public function payment()
    {
        return $this->belongsToMany(Fee::class);
    }
}
