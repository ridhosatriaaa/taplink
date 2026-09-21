<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrTag extends Model
{
    // Mengizinkan ID kustom (001, 002, dst)
    public $incrementing = false;
    protected $keyType = 'string';

    // Mengizinkan kolom diisi secara otomatis oleh Laravel
    protected $fillable = [
        'id',
        'place_id',
        'whatsapp_number',
        'activation_pin',
        'is_active',
    ];
}