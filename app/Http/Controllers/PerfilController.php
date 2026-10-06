<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('perfil.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Detecta archivo truncado por límite de PHP antes de validar
        if ($request->hasFile('foto_perfil') && !$request->file('foto_perfil')->isValid()) {
            return back()->withErrors([
                'foto_perfil' => 'El archivo es demasiado grande o no se pudo subir. Máximo 2MB.',
            ]);
        }

        // "sometimes" = solo valida/incluye el campo si vino en la petición
        // (los campos no marcados en el modal llegan deshabilitados, es decir, no se envían)
        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'email'       => 'sometimes|required|email|unique:users,email,' . $user->id,
            'telefono'    => 'sometimes|nullable|string|max:30',
            'orcid_id'    => 'sometimes|nullable|string|max:50',
            'scopus_id'   => 'sometimes|nullable|string|max:50',
            'doi'         => 'sometimes|nullable|string',
            'plaza'       => 'sometimes|nullable|string|max:100',
            'sni'         => 'sometimes|nullable|string|max:50',
            'foto_perfil' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'foto_perfil.image' => 'El archivo debe ser una imagen (jpg, jpeg o png).',
            'foto_perfil.mimes' => 'Solo se permiten imágenes en formato JPG o PNG.',
            'foto_perfil.max'   => 'La imagen no debe pesar más de 2MB.',
        ]);

        if (empty($validated)) {
            return back()->with('error', 'No seleccionaste ningún dato para modificar.');
        }

        if ($request->hasFile('foto_perfil')) {
            if ($user->foto_perfil) {
                Storage::disk('public')->delete($user->foto_perfil);
            }
            try {
                $validated['foto_perfil'] = $request->file('foto_perfil')->store('fotos_perfil', 'public');
            } catch (\Exception $e) {
                return back()->with('error', 'No se pudo guardar la imagen. Intenta de nuevo.');
            }
        }

        $user->update($validated);

        return back()->with('success', 'Datos actualizados correctamente.');
    }
}
