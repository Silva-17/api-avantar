<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuoteResponseController extends Controller
{
    public function store(Request $request, $id)
    {
        // Apenas admins podem responder
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'notes' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $quote = Quote::findOrFail($id);

        $data = [
            'quote_id' => $quote->id,
            'notes' => $request->input('notes'),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Salva na pasta 'quote_responses' dentro do disco public
            $path = $file->store('quote_responses', 'public');

            $data['file_path'] = $path;
            $data['original_file_name'] = $file->getClientOriginalName();
        }

        // Usa updateOrCreate para garantir apenas uma resposta por cotação (ou atualiza a existente)
        $response = QuoteResponse::updateOrCreate(
            ['quote_id' => $quote->id],
            $data
        );

        return response()->json($response, 201);
    }
}
