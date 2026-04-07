<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = trim($request->string('search'));

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('client_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $clients = $query->latest()->paginate(12);

        return response()->json($clients);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'client_code' => ['nullable', 'string', 'max:100', 'unique:clients,client_code'],
            'industry' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive', 'lead'])],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'onboarded_at' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        if (blank($validated['client_code'] ?? null)) {
            $validated['client_code'] = $this->generateClientCode($validated['company_name']);
        }

        $client = Client::create($validated);

        return response()->json($client, 201);
    }

    public function show(Client $client): JsonResponse
    {
        $client->load([
            'contacts',
            'notes',
            'tasks',
        ]);

        return response()->json($client);
    }

    public function update(Request $request, Client $client): JsonResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'client_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('clients', 'client_code')->ignore($client->id),
            ],
            'industry' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive', 'lead'])],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'onboarded_at' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $client->update($validated);

        return response()->json($client->fresh());
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
        return response()->json([
            'total_clients' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'lead_clients' => Client::where('status', 'lead')->count(),
            'open_tasks' => Task::whereIn('status', ['pending', 'in_progress'])->count(),
        ]);
    }

    protected function generateClientCode(string $companyName): string
    {
        $prefix = Str::upper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $companyName), 0, 4));

        if (blank($prefix)) {
            $prefix = 'CLNT';
        }

        do {
            $code = $prefix . '-' . random_int(1000, 9999);
        } while (Client::where('client_code', $code)->exists());

        return $code;
    }
}
