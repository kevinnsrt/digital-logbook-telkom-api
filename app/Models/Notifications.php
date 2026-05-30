<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    //
    protected $fillable = ['title', 'message', 'status_type', 'user_id'];
}
