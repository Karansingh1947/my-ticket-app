<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = \App\Models\Ticket::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_number' => Str::upper(Str::random(10)),
            'name' => $this->faker->sentence(6, true),
            'description' => $this->faker->paragraphs(3, true),
            'status' => $this->faker->randomElement(['pending','in_progress','completed','onhold']),
            'image_path' => null,
            'created_by' => User::factory(),
            'assigned_to' => null,
            'assigned_at' => null,
            'completed_at' => null,

        ];
    }
        /**
     * Mark ticket as assigned
     */
    public function assigned($userId = null): static
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'status' => 'in_progress',
                'assigned_to' => $userId ?? User::factory(),
                'assigned_at' => now(),
            ];
        });
    }

    /**
     * Mark ticket as completed
     */
    public function completed(): static
    {
        return $this->state(fn() => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
