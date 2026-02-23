<?php

namespace Database\Factories;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shippingTypes = ['regular', 'express', 'same_day', 'cargo'];
        $shippingType = $this->faker->randomElement($shippingTypes);

        // generate shipping const based on type
        $shippingCost = match ($shippingType) {
            'regular' => $this->faker->numberBetween(10000, 20000),
            'express' => $this->faker->numberBetween(20000, 30000),
            'same_day' => $this->faker->numberBetween(30000, 50000),
            'cargo' => $this->faker->numberBetween(50000, 100000),
            default => $this->faker->numberBetween(10000, 100000)
        };

        return [
            'id' => (string) Str::uuid(),
            'code' => 'ORD-'.$this->faker->unique()->numerify('########'),
            'buyer_id' => Buyer::factory(),
            'store_id' => Store::factory(),
            'address_id' => $this->faker->numberBetween(1, 1000),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'shipping' => $this->faker->randomElement(['JNE', 'JNT', 'Ninja Express', 'SiCepat', 'AnterAja', 'Paxel']),
            'shipping_type' => $shippingType,
            'shipping_cost' => $shippingCost,
            'tax' => 0,
            'grand_total' => 0,
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Transaction $transaction) {
            // create 1-5 transaction details for this transaction
            $numberOfDetails = $this->faker->numberBetween(1, 5);
            $subtotal = 0;

            for ($i = 0; $i < $numberOfDetails; $i++) {
                $product = Product::factory()->create([
                    'store_id' => $transaction->store_id,
                ]);
                $qty = $this->faker->numberBetween(1, 5);
                $subtotal += $product->price * $qty;

                TransactionDetail::factory()->create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'subtotal' => $product->price * $qty,
                ]);
            }

            $tax = round($subtotal * 0.11); // calculate tax (11% of subtotal)
            // calculate grand total
            $grandTotal = $subtotal + $tax + $transaction->shipping_cost;

            // update transaction with calculated totals
            $transaction->update([
                'tax' => $tax,
                'grand_total' => $grandTotal,
            ]);
        });
    }
}
