<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Não encontramos um usuário com esse endereço de e-mail.'], 404);
        }

        // Delete any existing reset tokens for this user
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Create a new token (using a 6 digit code)
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token), // Store hashed token
            'created_at' => Carbon::now()
        ]);

        try {
            Mail::to($user->email)->send(new PasswordResetMail($token));
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de recuperação: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao enviar e-mail de recuperação. Verifique os logs para mais detalhes.'], 500);
        }

        return response()->json(['message' => 'Código de redefinição de senha enviado para o seu e-mail!'], 200);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);

            $passwordReset = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
                 throw ValidationException::withMessages([
                    'token' => ['Este código de redefinição de senha é inválido.'],
                ]);
            }

            // Check if the token has expired (e.g., 60 minutes)
            if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                 throw ValidationException::withMessages([
                    'token' => ['Este código de redefinição de senha expirou.'],
                ]);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                 throw ValidationException::withMessages([
                    'email' => ['Não encontramos um usuário com esse endereço de e-mail.'],
                ]);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json(['message' => 'Sua senha foi redefinida com sucesso!'], 200);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao redefinir senha: ' . $e->getMessage());
            return response()->json(['message' => 'Erro interno ao redefinir senha.', 'debug_error' => $e->getMessage()], 500);
        }
    }
}
