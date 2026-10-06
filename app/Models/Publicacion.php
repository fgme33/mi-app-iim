<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'doi', 'anio'])]
class Publicacion extends Model
{
    protected $table = 'publicaciones';

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
