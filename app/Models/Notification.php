<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['registration_id', 'message', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    // 📌 Método para crear una notificación
    public static function createNotification($registrationId, $message)
    {
        return self::create([
            'registration_id' => $registrationId,
            'message' => $message,
            'read_at' => null,
        ]);
    }
}
