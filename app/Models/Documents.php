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
        'taken_at',
        'jangka_waktu'
    ];

    public function user(): BelongsTo
    {
        // Parameter kedua adalah foreign key di tabel documents kamu (misal: user_id)
        return $this->belongsTo(User::class, 'user_id');
    }
}
