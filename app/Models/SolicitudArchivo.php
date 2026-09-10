<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudArchivo extends Model
{
    protected $table = 'solicitud_archivos';

    protected $fillable = ['solicitud_id', 'nombre_original', 'ruta_archivo'];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
}
