<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'adv_id' => $this->faker->word,
            'peak_price' => $this->faker->randomDigitNotNull,
            'is_allow_bulk' => $this->faker->randomDigitNotNull,
            'is_allow_unbind' => $this->faker->randomDigitNotNull,
            'punish' => $this->faker->randomDigitNotNull,
            'status' => $this->faker->randomDigitNotNull,
            'created_at' => $this->faker->word,
            'updated_at' => $this->faker->word,
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
