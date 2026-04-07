<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'client_code' => $this->client_code,
            'industry' => $this->industry,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'status' => $this->status,
            'hourly_rate' => $this->hourly_rate,
            'credit_limit' => $this->credit_limit,
            'onboarded_at' => optional($this->onboarded_at)->format('Y-m-d'),
            'description' => $this->description,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'notes' => NoteResource::collection($this->whenLoaded('notes')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'contacts_count' => $this->whenCounted('contacts'),
            'notes_count' => $this->whenCounted('notes'),
            'tasks_count' => $this->whenCounted('tasks'),
        ];
    }
}
