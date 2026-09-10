<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCompra extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_compra';

    protected $fillable = [
        'folio',
        'user_id',
        'email_notificacion',
        'articulo_solicitado',
	'justificacion',
	'archivo',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
