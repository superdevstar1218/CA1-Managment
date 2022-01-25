<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'project_id',
        'project_type',
        'content',
        'start_date',
        'is_done',
        'comment'
    ];
    /**
     * @var int|mixed|string|null
     */

    public  function  user(){
        return $this->belongsTo(User::class) ;
    }
}
