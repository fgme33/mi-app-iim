<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudServicio extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_servicio';

    protected $fillable = [
        'folio',
        'user_id',
        'email_notificacion',
	'descripcion_problema',
	'archivo',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
