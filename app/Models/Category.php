<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

   public function field(): \Illuminate\Database\Eloquent\Relations\HasMany
   {
        return $this->hasMany(Field::class);
    }
}
