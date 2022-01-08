<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'cur_status', 'before_status', 'start_at', 'end_at', 'comment', 'project_id'];
    public $timestamps = false;
}
