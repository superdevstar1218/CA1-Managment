<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyLog extends Model
{
    use HasFactory;

    public function customer(){
        return $this->belongsTo(Customer::class) ;
    }
    
    public function project(){
        return $this->belongsTo(Project::class) ;
    }

    public function currency(){
        return $this->belongsTo(Currency::class) ;
    }
}
