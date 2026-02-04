<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function store(StoreQuoteRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $quote = $request->user()->quotes()->create($validatedData);

            if ($request->hasFile('documentos')) {
                foreach ($request->file('documentos') as $file) {
                    $path = $file->store('quotes', 'public');
                    $quote->documents()->create([
                        'caminho_arquivo' => $path,
                        'nome_original' => $file->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();

            return response()->json($quote->load('documents'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao salvar o orçamento.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $quote = Quote::with('documents')->findOrFail($id);
        return response()->json($quote);
    }
}
