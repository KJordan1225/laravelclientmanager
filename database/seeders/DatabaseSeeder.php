<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Client::factory()
            ->count(20)
            ->create()
            ->each(function ($client) {
                $contactCount = fake()->numberBetween(1, 3);
                $noteCount = fake()->numberBetween(1, 5);
                $taskCount = fake()->numberBetween(1, 6);

                $contacts = \App\Models\Contact::factory()
                    ->count($contactCount)
                    ->create([
                        'client_id' => $client->id,
                    ]);

                if ($contacts->isNotEmpty()) {
                    $contacts->first()->update(['is_primary' => true]);
                }

                \App\Models\Note::factory()
                    ->count($noteCount)
                    ->create([
                        'client_id' => $client->id,
                    ]);

                \App\Models\Task::factory()
                    ->count($taskCount)
                    ->create([
                        'client_id' => $client->id,
                    ]);
            });
    }
}
