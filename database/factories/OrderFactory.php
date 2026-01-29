<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'payment_method' => $this->faker->randomElement(['cash', 'receipt']),
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
            'status' => 'pending',
            'receipt_path' => null,
        ];
    }

    /**
     * Indicate that the order was paid with receipt.
     */
    public function withReceipt(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'receipt',
            'receipt_path' => 'receipts/test-receipt.jpg',
        ]);
    }

    /**
     * Indicate that the order was paid with cash.
     */
    public function cash(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cash',
            'receipt_path' => null,
        ]);
    }
}
