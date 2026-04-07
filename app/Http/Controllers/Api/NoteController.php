<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Note::with('client')->latest();

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->integer('client_id'));
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'created_by' => ['nullable', 'string', 'max:255'],
        ]);

        $note = Note::create($validated);

        return response()->json($note->load('client'), 201);
    }

    public function show(Note $note): JsonResponse
    {
        return response()->json($note->load('client'));
    }

    public function update(Request $request, Note $note): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'created_by' => ['nullable', 'string', 'max:255'],
        ]);

        $note->update($validated);

        return response()->json($note->fresh()->load('client'));
    }

    public function destroy(Note $note): JsonResponse
    {
        $note->delete();

        return response()->json([
            'message' => 'Note deleted successfully.',
        ]);
    }
}
