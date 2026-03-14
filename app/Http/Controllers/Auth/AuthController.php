<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\WelcomeAndVerifyEmail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'apellido_paterno' => ['required', 'string', 'max:100'],
            'apellido_materno' => ['nullable', 'string', 'max:100'],
            'celular'          => ['required', 'string', 'max:20'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user  = User::create([...$validated, 'role' => UserRole::Cliente]);
        $token = $user->generateVerificationToken();

        Mail::to($user->email)->send(new WelcomeAndVerifyEmail($user, $token));

        $authToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'    => $user,
            'token'   => $authToken,
            'message' => 'Registro exitoso. Revisa tu correo para verificar tu cuenta.',
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $user  = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    public function verifyEmail(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        $user = User::where('email_verification_token', $request->token)->first();

        if (! $user) {
            return response()->json(['message' => 'Token de verificacion invalido o expirado.'], 422);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'El email ya fue verificado.', 'already_verified' => true]);
        }

        if ($user->isVerificationTokenExpired()) {
            return response()->json([
                'message' => 'El enlace ha expirado. Solicita uno nuevo desde tu dashboard.',
                'expired'  => true,
            ], 422);
        }

        $user->markEmailAsVerified();
        $user->update([
            'email_verification_token'          => null,
            'email_verification_expires_at'     => null,
        ]);
        event(new Verified($user));

        return response()->json([
            'message' => '¡Email verificado correctamente! Bienvenido a Zendo.',
            'user'    => $user->fresh(),
        ]);
    }

    public function resendVerification(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'El email ya fue verificado.'], 422);
        }

        $token = $user->generateVerificationToken();
        Mail::to($user->email)->send(new WelcomeAndVerifyEmail($user, $token));

        return response()->json(['message' => 'Correo de verificacion reenviado.']);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesion cerrada correctamente.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }
}
