<?php

namespace Database\Factories;

use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreBalanceHistory>
 */
class StoreBalanceHistoryFactory extends Factory
{
    protected $model = StoreBalanceHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'store_balance_id' => StoreBalance::factory(),
            'type' => 'initial',
            'reference_id' => null,
            'reference_type' => null,
            'amount' => $this->faker->randomFloat(2, 0, 1000000),
            'remarks' => 'Pembuatan Toko Baru',
        ];
    }
}
