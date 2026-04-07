<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        $company = fake()->company();

        return [
            'company_name' => $company,
            'client_code' => strtoupper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $company), 0, 4)) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'industry' => fake()->randomElement([
                'Technology',
                'Healthcare',
                'Finance',
                'Education',
                'Retail',
                'Manufacturing',
                'Construction',
            ]),
            'website' => fake()->optional()->url(),
            'email' => fake()->optional()->companyEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'address' => fake()->optional()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => 'United States',
            'status' => fake()->randomElement(['active', 'inactive', 'lead']),
            'hourly_rate' => fake()->optional()->randomFloat(2, 50, 300),
            'credit_limit' => fake()->optional()->randomFloat(2, 1000, 50000),
            'onboarded_at' => fake()->optional()->date(),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
