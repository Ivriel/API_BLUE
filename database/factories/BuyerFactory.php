<?php

namespace Database\Factories;

use App\Helpers\ImageHelper\ImageHelper;
use App\Models\Buyer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buyer>
 */
class BuyerFactory extends Factory
{
    protected $model = Buyer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imageHelper = new ImageHelper;

        return [
            'id' => (string) Str::uuid(),
            'user_id' => User::factory(),
            'profile_picture' => $imageHelper->storeAndResizeImage(
                $imageHelper->createDummyImageWithTextSizeAndPosition(250, 250, 'center', 'center', 'random', 'medium'),
                'buyer',
                250,
                250
            ),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}
