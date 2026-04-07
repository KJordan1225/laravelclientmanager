<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Contact::with('client')->latest();

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->integer('client_id'));
        }

        return response()->json($query->get());
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (($validated['is_primary'] ?? false) === true) {
            Contact::where('client_id', $validated['client_id'])->update(['is_primary' => false]);
        }

        $contact = Contact::create($validated);

        return response()->json($contact->load('client'), 201);
    }

    public function show(Contact $contact): JsonResponse
    {
        return response()->json($contact->load('client'));
    }

    public function update(UpdateContactRequest $request, Contact $contact): JsonResponse
    {
        $validated = $request->validated();

        if (($validated['is_primary'] ?? false) === true) {
            Contact::where('client_id', $validated['client_id'])
                ->where('id', '!=', $contact->id)
                ->update(['is_primary' => false]);
        }

        $contact->update($validated);

        return response()->json($contact->fresh()->load('client'));
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully.',
        ]);
    }
}
