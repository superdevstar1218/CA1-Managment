<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'comment'];
    public $timestamps = false;

    public function registries(){
        return $this->hasMany(Registry::class) ;
    }
}
