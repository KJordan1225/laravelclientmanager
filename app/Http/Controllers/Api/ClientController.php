<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Task;
use App\Services\ClientCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        protected ClientCodeService $clientCodeService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $sort = $request->string('sort', 'latest')->toString();

        $query = Client::query()->withCount(['contacts', 'notes', 'tasks']);

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('client_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        match ($sort) {
            'company_asc' => $query->orderBy('company_name'),
            'company_desc' => $query->orderByDesc('company_name'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $clients = $query->paginate(10);

        return response()->json([
            'data' => ClientResource::collection($clients->items()),
            'meta' => [
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'per_page' => $clients->perPage(),
                'total' => $clients->total(),
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem(),
            ],
            'links' => [
                'prev' => $clients->previousPageUrl(),
                'next' => $clients->nextPageUrl(),
            ],
        ]);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (blank($validated['client_code'] ?? null)) {
            $validated['client_code'] = $this->clientCodeService->generate($validated['company_name']);
        }

        $client = Client::create($validated);

        return response()->json(new ClientResource($client), 201);
    }

    public function show(Client $client): JsonResponse
    {
        $client->load(['contacts', 'notes', 'tasks']);

        return response()->json(new ClientResource($client));
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());

        return response()->json(new ClientResource($client->fresh()));
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully.',
        ]);
    }

    public function stats(): JsonResponse
    {
        $recentClients = Client::latest()
            ->take(5)
            ->get(['id', 'company_name', 'client_code', 'status', 'created_at']);

        return response()->json([
            'total_clients' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'lead_clients' => Client::where('status', 'lead')->count(),
            'open_tasks' => Task::whereIn('status', ['pending', 'in_progress'])->count(),
            'recent_clients' => $recentClients,
        ]);
    }
}
