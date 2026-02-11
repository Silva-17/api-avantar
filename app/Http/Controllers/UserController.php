<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Lista todos os usuários.
     */
    public function index()
    {
        // Retorna todos os usuários, ordenados por nome
        return User::orderBy('name')->get();
    }

    /**
     * Cria um novo usuário.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'unit' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:admin,consultant'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'unit' => $request->unit,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }
}
