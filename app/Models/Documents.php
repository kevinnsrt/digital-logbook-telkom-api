<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    //
    protected $table = 'documents';
    protected $fillable = [
        'title',
        'customer',
        'mitra',
        'price',
        'status',
        'user_id',
        'admin_id',
        'taken_at'
    ];
}
