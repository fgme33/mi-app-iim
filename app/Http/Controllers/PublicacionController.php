<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicacionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doi'  => 'required|string|max:255',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        ], [
            'doi.required'  => 'El DOI es obligatorio.',
            'anio.required' => 'El año es obligatorio.',
            'anio.integer'  => 'El año debe ser un número.',
        ]);

        Auth::user()->publicaciones()->create($validated);

        return back()->with('success', 'Publicación agregada correctamente.');
    }

    public function destroy($id)
    {
        $publicacion = Publicacion::findOrFail($id);

        if ($publicacion->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'No autorizado.');
        }

        $publicacion->delete();

        return back()->with('success', 'Publicación eliminada.');
    }
}
