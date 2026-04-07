<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
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

    public function store(StoreNoteRequest $request): JsonResponse
    {
        $note = Note::create($request->validated());

        return response()->json($note->load('client'), 201);
    }

    public function show(Note $note): JsonResponse
    {
        return response()->json($note->load('client'));
    }

    public function update(UpdateNoteRequest $request, Note $note): JsonResponse
    {
        $note->update($request->validated());

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