<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraphs(3, true),
            'created_by' => fake()->name(),
        ];
    }
}
