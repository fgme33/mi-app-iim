<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'folio',
        'tipo',
        'user_id',
        'email_notificacion',
        'descripcion',
        'estado'
    ];

    // Relación con el usuario creador
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con los archivos adjuntos
    public function archivos()
    {
        return $this->hasMany(SolicitudArchivo::class, 'solicitud_id');
    }
}
