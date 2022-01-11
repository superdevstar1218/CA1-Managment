<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'status'];
    public $timestamps = false;

    public function registries(){
        return $this->hasMany(Registry::class) ;
    }
    
    public function moneylogs(){
        return $this->hasMany(MoneyLog::class) ;
    }
}
