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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Quote::query();

        // Select only the necessary fields to reduce the payload
        $query->select('id', 'quote_status_id', 'quotable_type', 'quotable_id', 'user_id', 'created_at');

        // Eager load the user's name and the status name
        // Also eager load quotable to get client name in the response if needed
        $query->with(['user:id,name', 'quoteStatus:id,name', 'quotable']);

        // Filter by User (Consultant)
        if ($user->role === 'admin') {
            if ($request->has('user_id')) {
                $query->where('user_id', $request->input('user_id'));
            }
        } else {
            // Non-admins can only see their own quotes
            $query->where('user_id', $user->id);
        }

        // Filter by Status
        if ($request->has('status')) {
            $query->where('quote_status_id', $request->input('status'));
        }

        // Filter by Service Type (Quotable Type)
        if ($request->has('service_type')) {
            $serviceType = $request->input('service_type');
            $modelClass = $this->getQuotableClassFromType($serviceType);
            if ($modelClass) {
                $query->where('quotable_type', $modelClass);
            }
        }

        // Filter by Client Name
        if ($request->has('client_name')) {
            $clientName = $request->input('client_name');
            $query->whereHasMorph('quotable', '*', function (Builder $query, $type) use ($clientName) {
                // Check which column to search based on the model type
                if (in_array($type, [
                    QuoteAuto::class,
                    QuoteMotorcycle::class,
                    QuoteTruck::class,
                    QuoteLifeIndividual::class,
                    QuoteConsortium::class
                ])) {
                    $query->where('nome_completo', 'like', "%{$clientName}%");
                } elseif (in_array($type, [
                    QuoteLifeGroup::class,
                    QuoteResidential::class,
                    QuoteCondominium::class
                ])) {
                    $query->where('razao_social', 'like', "%{$clientName}%");
                }
            });
        }

        $quotes = $query->get()->map(function ($quote) {
            // Simplify the quotable_type to just the type name
            $quote->tipo_formulario = last(explode('\\', $quote->quotable_type));

            // Extract client name for easier display in frontend
            $quotable = $quote->quotable;
            if ($quotable) {
                $quote->client_name = $quotable->nome_completo ?? $quotable->razao_social ?? 'N/A';
            }

            unset($quote->quotable); // remove the full object if not needed, or keep it
            unset($quote->quotable_type); // remove the full class name

            return $quote;
        });

        return response()->json($quotes);
    }

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

            // Create quote with default status (0 - Em Fila)
            $quote = Quote::create([
                'user_id' => $request->user()->id,
                'quotable_id' => $quotable->id,
                'quotable_type' => get_class($quotable),
                'quote_status_id' => 0,
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
            $quote->load('quoteStatus'); // Load the status relationship

            return response()->json($quote, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao salvar o orçamento.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        // Carrega todas as respostas (responses) junto com os outros relacionamentos
        $quote = Quote::with(['documents', 'quotable', 'quoteStatus', 'responses'])->findOrFail($id);

        if ($quote->quotable_type === QuoteLifeIndividual::class) {
            $quote->load('quotable.beneficiaries');
        }

        return response()->json($quote);
    }

    public function updateStatus(Request $request, $id)
    {
        // Apenas admins podem alterar o status
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status_id' => 'required|exists:quote_statuses,id',
        ]);

        $quote = Quote::findOrFail($id);
        $quote->quote_status_id = $request->input('status_id');
        $quote->save();

        // Recarrega o relacionamento para retornar o objeto completo
        $quote->load('quoteStatus');

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

    private function getQuotableClassFromType(string $type): ?string
    {
        $map = [
            'auto' => QuoteAuto::class,
            'motorcycle' => QuoteMotorcycle::class,
            'truck' => QuoteTruck::class,
            'life_individual' => QuoteLifeIndividual::class,
            'life_group' => QuoteLifeGroup::class,
            'residential' => QuoteResidential::class,
            'condominium' => QuoteCondominium::class,
            'consortium' => QuoteConsortium::class,
        ];

        return $map[$type] ?? null;
    }
}
