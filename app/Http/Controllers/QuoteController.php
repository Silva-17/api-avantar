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
use App\Http\Requests\UpdateAutoQuoteRequest;
use App\Http\Requests\UpdateCondominiumQuoteRequest;
use App\Http\Requests\UpdateConsortiumQuoteRequest;
use App\Http\Requests\UpdateLifeGroupQuoteRequest;
use App\Http\Requests\UpdateLifeIndividualQuoteRequest;
use App\Http\Requests\UpdateMotorcycleQuoteRequest;
use App\Http\Requests\UpdateResidentialQuoteRequest;
use App\Http\Requests\UpdateTruckQuoteRequest;
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
use Illuminate\Validation\ValidationException;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Quote::query();

        // Select only the necessary fields to reduce the payload
        // Added 'attendant_id' to ensure the relationship can be loaded
        $query->select('id', 'quote_status_id', 'quotable_type', 'quotable_id', 'user_id', 'attendant_id', 'created_at');

        // Eager load the user's name and the status name
        // Also eager load quotable to get client name in the response if needed
        $query->with(['user:id,name', 'quoteStatus:id,name', 'quotable', 'attendant:id,name']);

        // Filter by User (Consultant)
        if (in_array($user->role, ['admin', 'gestor'])) {
             // Admin e Gestor podem ver tudo (ou filtre conforme regra de negócio)
            if ($request->has('user_id')) {
                $query->where('user_id', $request->input('user_id'));
            }
        } elseif ($user->role === 'atendente') {
            // Atendente só pode ver as cotações que estão no nome dele (atribuídas a ele)
            $query->where('attendant_id', $user->id);
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

        // Sorting
        $sortOrder = $request->input('sort_order', 'desc');
        $direction = ($sortOrder === 'cresc') ? 'asc' : 'desc';
        $query->orderBy('created_at', $direction);

        $perPage = $request->input('per_page', 10);
        $quotes = $query->paginate($perPage);

        $quotes->getCollection()->transform(function ($quote) {
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
        // Removido a restrição :id,name do attendant para garantir que venha os dados completos se houver problema
        $quote = Quote::with(['documents', 'quotable', 'quoteStatus', 'responses', 'attendant'])->findOrFail($id);

        if ($quote->quotable_type === QuoteLifeIndividual::class) {
            $quote->load('quotable.beneficiaries');
        }

        return response()->json($quote);
    }

    public function update(Request $request, $id)
    {
        $quote = Quote::with('quotable')->findOrFail($id);

        // Check if the authenticated user is the owner of the quote
        if ($request->user()->id !== $quote->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Determine the type from the existing model
        $type = $this->getTypeFromQuotableClass(get_class($quote->quotable));

        if (!$type) {
            return response()->json(['message' => 'Tipo de cotação inválido ou não encontrado.'], 400);
        }

        // Get validation rules specific to this quote type for UPDATE
        $rules = $this->getUpdateRulesForType($type);

        // Remove 'tipo_seguro' from request data to avoid any confusion during validation
        $requestData = $request->except('tipo_seguro');

        try {
            $validatedData = validator($requestData, $rules)->validate();
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Dados inválidos.', 'errors' => $e->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $quotable = $quote->quotable;
            $quotable->update($validatedData);

            // Reset the status to "em fila" (0)
            $quote->quote_status_id = 0;
            $quote->save();

            DB::commit();

            $this->loadRelationships($quote, $type);
            $quote->load('quoteStatus');

            return response()->json($quote);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao atualizar o orçamento.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // Apenas admins podem alterar o status
        if (!in_array($request->user()->role, ['admin', 'gestor'])) {
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

    public function assignAttendant(Request $request, $id)
    {
        // Apenas admins ou gestores podem designar um atendente
        if (!in_array($request->user()->role, ['admin', 'gestor'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'attendant_id' => 'required|exists:users,id',
        ]);

        $quote = Quote::findOrFail($id);
        $quote->attendant_id = $request->input('attendant_id');
        $quote->save();

        // Recarrega o relacionamento para retornar o objeto completo
        $quote->load('attendant');

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

    private function getUpdateRulesForType(string $type): array
    {
        $map = [
            'auto' => UpdateAutoQuoteRequest::class,
            'motorcycle' => UpdateMotorcycleQuoteRequest::class,
            'truck' => UpdateTruckQuoteRequest::class,
            'life_individual' => UpdateLifeIndividualQuoteRequest::class,
            'life_group' => UpdateLifeGroupQuoteRequest::class,
            'residential' => UpdateResidentialQuoteRequest::class,
            'condominium' => UpdateCondominiumQuoteRequest::class,
            'consortium' => UpdateConsortiumQuoteRequest::class,
        ];
        // We don't need to unset 'tipo_seguro' here because Update requests don't have it in rules
        return (new $map[$type])->rules();
    }

    private function createQuotable(string $type, array $data)
    {
        $modelClass = $this->getQuotableClassFromType($type);

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

    private function getTypeFromQuotableClass(string $class): ?string
    {
        $map = [
            QuoteAuto::class => 'auto',
            QuoteMotorcycle::class => 'motorcycle',
            QuoteTruck::class => 'truck',
            QuoteLifeIndividual::class => 'life_individual',
            QuoteLifeGroup::class => 'life_group',
            QuoteResidential::class => 'residential',
            QuoteCondominium::class => 'condominium',
            QuoteConsortium::class => 'consortium',
        ];

        return $map[$class] ?? null;
    }
}
