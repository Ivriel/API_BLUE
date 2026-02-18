<?php

namespace Database\Factories;

use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Withdrawal>
 */
class WithdrawalFactory extends Factory
{
    protected $model = Withdrawal::class;

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
            'amount' => function (array $attributes) {
                $storeBalance = StoreBalance::find($attributes['store_balance_id']);

                return $this->faker->randomFloat(2, 0, $storeBalance->balance);
            },
            'bank_account_name' => $this->faker->name,
            'bank_account_number' => $this->faker->numerify('##########'),
            'bank_name' => $this->faker->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI']),
            'status' => 'pending',
        ];
    }

    public function configure() // setelah di create, menambah history dan memotong saldo
    {
        return $this->afterCreating(function (Withdrawal $withdrawal) {
            // Satu entri history untuk penarikan: amount negatif = uang keluar (konsisten dengan perhitungan saldo)
            StoreBalanceHistory::create([
                'id' => (string) Str::uuid(),
                'store_balance_id' => $withdrawal->storeBalance->id,
                'type' => 'withdraw',
                'reference_id' => $withdrawal->id,
                'reference_type' => Withdrawal::class,
                'amount' => -$withdrawal->amount,
                'remarks' => "Permintaan penarikan dana ke {$withdrawal->bank_name} - {$withdrawal->bank_account_number} telah diproses",
            ]);

            $withdrawal->update(['status' => 'approved']);

            $withdrawal->storeBalance->update([
                'balance' => $withdrawal->storeBalance->balance - $withdrawal->amount,
            ]);
        });
    }
}
