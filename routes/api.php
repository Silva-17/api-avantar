<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\QuoteResponseController;
use App\Http\Controllers\QuoteStatusController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rota pública de login
Route::post('/login', [AuthController::class, 'login']);

// Rotas de recuperação de senha
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);

Route::middleware('auth:sanctum')->group(function () {
    // Rota para obter o usuário autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rota de logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rotas de Orçamentos
    Route::get('/quotes', [QuoteController::class, 'index']);
    Route::post('/quotes', [QuoteController::class, 'store']);
    Route::get('/quotes/{id}', [QuoteController::class, 'show']);
    Route::put('/quotes/{id}', [QuoteController::class, 'update']);
    Route::patch('/quotes/{id}/status', [QuoteController::class, 'updateStatus']);
    Route::patch('/quotes/{id}/attendant', [QuoteController::class, 'assignAttendant']); // Nova rota para designar atendente
    Route::post('/quotes/{id}/response', [QuoteResponseController::class, 'store']); // Nova rota para resposta

    // Rota para listar status de cotações
    Route::get('/quote-statuses', [QuoteStatusController::class, 'index']);

    // Rotas de Usuários
    Route::get('/users/attendants', [UserController::class, 'listAttendants']); // Rota específica para listar atendentes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive']);

    // Rotas para documentos protegidos
    Route::get('/documents/{document}/download', [DocumentController::class, 'download']);
    Route::get('/documents/{document}/view', [DocumentController::class, 'view']);

    // Rota para marcar mensagens como lidas
    Route::post('/mensagens/marcar-como-lida', [QuoteResponseController::class, 'markAsRead']);
});
