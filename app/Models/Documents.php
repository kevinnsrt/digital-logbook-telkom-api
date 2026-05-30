<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'taken_at',
        'jangka_waktu'
    ];

public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
