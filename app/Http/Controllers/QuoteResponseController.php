<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuoteResponseController extends Controller
{
    public function store(Request $request, $id)
    {
        $user = $request->user();
        Log::info('QuoteResponseController@store: User role is ' . $user->role);

        // Consultor não pode enviar mensagens
        if ($user->role === 'consultor') {
            return response()->json(['message' => 'Unauthorized. Consultants cannot send messages.'], 403);
        }

        $request->validate([
            'notes' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $quote = Quote::findOrFail($id);

        $data = [
            'quote_id' => $quote->id,
            'notes' => $request->input('notes'),
            'lida' => false,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Salva na pasta 'quote_responses' dentro do disco public
            $path = $file->store('quote_responses', 'public');

            $data['file_path'] = $path;
            $data['original_file_name'] = $file->getClientOriginalName();
        }

        // Cria uma nova resposta (permite múltiplas respostas para a mesma cotação)
        $response = QuoteResponse::create($data);

        return response()->json($response, 201);
    }

    public function markAsRead(Request $request)
    {
        // Apenas o consultor pode marcar como lida
        if ($request->user()->role !== 'consultor') {
            return response()->json(['message' => 'Unauthorized. Only consultants can mark messages as read.'], 403);
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:quote_responses,id',
        ]);

        $ids = $request->input('ids');
        Log::info('Marking messages as read (individual update)', ['ids' => $ids, 'user_id' => $request->user()->id]);

        $updatedCount = 0;
        $responses = QuoteResponse::whereIn('id', $ids)->get();

        foreach ($responses as $response) {
            $response->lida = true;
            if ($response->save()) {
                $updatedCount++;
            }
        }

        Log::info("Updated {$updatedCount} messages individually.");

        return response()->json([
            'message' => 'Messages marked as read',
            'updated_count' => $updatedCount
        ]);
    }
}
