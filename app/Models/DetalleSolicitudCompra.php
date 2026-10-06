<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['solicitud_compra_id', 'articulo_id', 'cantidad'])]
class DetalleSolicitudCompra extends Model
{
    protected $table = 'detalle_solicitud_compra';

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudCompra::class, 'solicitud_compra_id');
    }
}
