<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('perfil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

	// Detecta si el archivo llegó truncado por límite de PHP
    if ($request->hasFile('foto_perfil') && !$request->file('foto_perfil')->isValid()) {
        return back()->withErrors([
            'foto_perfil' => 'El archivo es demasiado grande o no se pudo subir. Máximo 2MB.',
        ]);
    }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'telefono'    => 'nullable|string|max:30',
            'orcid_id'    => 'nullable|string|max:50',
            'scopus_id'   => 'nullable|string|max:50',
            'doi'         => 'nullable|string',
            'plaza'       => 'nullable|string|max:100',
            'sni'         => 'nullable|string|max:50',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
		
	    ] , [
    'foto_perfil.image' => 'El archivo debe ser una imagen (jpg, jpeg o png).',
    'foto_perfil.mimes' => 'Solo se permiten imágenes en formato JPG o PNG.',
    'foto_perfil.max'   => 'La imagen no debe pesar más de 2MB.',
        ]);

        if ($request->hasFile('foto_perfil')) {
            $validated['foto_perfil'] = $request->file('foto_perfil')->store('fotos_perfil', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
