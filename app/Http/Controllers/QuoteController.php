<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutoQuoteRequest;
use App\Http\Requests\StoreMotorcycleQuoteRequest;
use App\Models\Quote;
use App\Models\QuoteAuto;
use App\Models\QuoteMotorcycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tipo_seguro' => 'required|string|in:auto,motorcycle',
        ]);

        $type = $request->input('tipo_seguro');
        $rules = $this->getRulesForType($type);
        $validatedData = $request->validate($rules);

        $proponentData = $this->extractProponentData($validatedData);
        $specificData = array_diff_key($validatedData, $proponentData);

        try {
            DB::beginTransaction();

            // Passo 1: Crie o registro específico primeiro (auto ou moto).
            $quotable = $this->createQuotable($type, $specificData);

            // Passo 2: Crie o orçamento mestre e associe ao registro específico.
            $quoteData = array_merge(
                $proponentData,
                [
                    'user_id' => $request->user()->id,
                    'quotable_id' => $quotable->id,
                    'quotable_type' => get_class($quotable),
                ]
            );
            $quote = Quote::create($quoteData);

            // Salva os documentos, se houver.
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

            return response()->json($quote->load(['documents', 'quotable']), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao salvar o orçamento.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $quote = Quote::with(['documents', 'quotable'])->findOrFail($id);
        return response()->json($quote);
    }

    private function getRulesForType(string $type): array
    {
        $map = [
            'auto' => StoreAutoQuoteRequest::class,
            'motorcycle' => StoreMotorcycleQuoteRequest::class,
        ];
        return (new $map[$type])->rules();
    }

    private function createQuotable(string $type, array $data)
    {
        $map = [
            'auto' => QuoteAuto::class,
            'motorcycle' => QuoteMotorcycle::class,
        ];
        return $map[$type]::create($data);
    }

    private function extractProponentData(array $validatedData): array
    {
        return [
            'tipo_operacao' => $validatedData['tipo_operacao'],
            'nome_completo' => $validatedData['nome_completo'],
            'data_nascimento' => $validatedData['data_nascimento'],
            'sexo' => $validatedData['sexo'],
            'cpf_cnpj' => $validatedData['cpf_cnpj'],
            'telefone' => $validatedData['telefone'],
            'email' => $validatedData['email'],
            'cep' => $validatedData['cep'],
            'profissao' => $validatedData['profissao'],
            'estado_civil' => $validatedData['estado_civil'],
        ];
    }
}
