<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function checkAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $users = User::latest()->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->checkAdmin();

        return view('users.create');
    }	

public function store(Request $request)
{
    $this->checkAdmin();

    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8',
        'role'     => 'required|string|in:user,admin',
    ]);

    User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role'     => $validated['role'],
    ]);

    // Redirección normal de Laravel
    return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
}
    public function edit(User $user)
    {
        $this->checkAdmin();

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'  => 'required|string|in:user,admin',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $this->checkAdmin();

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
