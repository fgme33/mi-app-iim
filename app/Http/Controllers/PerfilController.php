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
        ]);

        if ($request->hasFile('foto_perfil')) {
            $validated['foto_perfil'] = $request->file('foto_perfil')->store('fotos_perfil', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
