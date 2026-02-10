<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutoQuoteRequest;
use App\Http\Requests\StoreCondominiumQuoteRequest;
use App\Http\Requests\StoreConsortiumQuoteRequest;
use App\Http\Requests\StoreLifeGroupQuoteRequest;
use App\Http\Requests\StoreLifeIndividualQuoteRequest;
use App\Http\Requests\StoreMotorcycleQuoteRequest;
use App\Http\Requests\StoreResidentialQuoteRequest;
use App\Http\Requests\StoreTruckQuoteRequest;
use App\Models\Quote;
use App\Models\QuoteAuto;
use App\Models\QuoteCondominium;
use App\Models\QuoteConsortium;
use App\Models\QuoteLifeGroup;
use App\Models\QuoteLifeIndividual;
use App\Models\QuoteMotorcycle;
use App\Models\QuoteResidential;
use App\Models\QuoteTruck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tipo_seguro' => 'required|string|in:auto,motorcycle,truck,life_individual,life_group,residential,condominium,consortium',
        ]);

        $type = $request->input('tipo_seguro');
        $rules = $this->getRulesForType($type);
        $validatedData = $request->validate($rules);

        try {
            DB::beginTransaction();

            $quotable = $this->createQuotable($type, $validatedData);

            $quote = Quote::create([
                'user_id' => $request->user()->id,
                'quotable_id' => $quotable->id,
                'quotable_type' => get_class($quotable),
            ]);

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

            $this->loadRelationships($quote, $type);

            return response()->json($quote, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao salvar o orçamento.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $quote = Quote::with(['documents', 'quotable'])->findOrFail($id);

        if ($quote->quotable_type === QuoteLifeIndividual::class) {
            $quote->load('quotable.beneficiaries');
        }

        return response()->json($quote);
    }

    private function getRulesForType(string $type): array
    {
        $map = [
            'auto' => StoreAutoQuoteRequest::class,
            'motorcycle' => StoreMotorcycleQuoteRequest::class,
            'truck' => StoreTruckQuoteRequest::class,
            'life_individual' => StoreLifeIndividualQuoteRequest::class,
            'life_group' => StoreLifeGroupQuoteRequest::class,
            'residential' => StoreResidentialQuoteRequest::class,
            'condominium' => StoreCondominiumQuoteRequest::class,
            'consortium' => StoreConsortiumQuoteRequest::class,
        ];
        return (new $map[$type])->rules();
    }

    private function createQuotable(string $type, array $data)
    {
        $modelClass = [
            'auto' => QuoteAuto::class,
            'motorcycle' => QuoteMotorcycle::class,
            'truck' => QuoteTruck::class,
            'life_individual' => QuoteLifeIndividual::class,
            'life_group' => QuoteLifeGroup::class,
            'residential' => QuoteResidential::class,
            'condominium' => QuoteCondominium::class,
            'consortium' => QuoteConsortium::class,
        ][$type];

        if ($type === 'life_individual') {
            $beneficiaries = $data['beneficiarios'];
            unset($data['beneficiarios']);
            $lifeQuote = $modelClass::create($data);
            $lifeQuote->beneficiaries()->createMany($beneficiaries);
            return $lifeQuote;
        }

        return $modelClass::create($data);
    }

    private function loadRelationships(Quote $quote, string $type): void
    {
        $relations = ['documents', 'quotable'];
        if ($type === 'life_individual') {
            $relations[] = 'quotable.beneficiaries';
        }
        $quote->load($relations);
    }
}
