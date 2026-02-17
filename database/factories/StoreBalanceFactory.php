<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\StoreBalance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreBallance>
 */
class StoreBalanceFactory extends Factory
{
    protected $model = StoreBalance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'store_id' => Store::factory(),
            'balance' => $this->faker->randomFloat(2, 0, 1000000),
        ];
    }
}
