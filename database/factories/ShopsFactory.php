<?php

namespace Database\Factories;

use App\Models\shops;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = shops::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'advertiser_id' => $this->faker->randomDigitNotNull,
            'advertiser_name' => $this->faker->word,
            'is_valid' => $this->faker->word,
            'account_role' => $this->faker->word,
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
