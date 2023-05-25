<?php

namespace Database\Factories;

use App\Models\AlarmLogs;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlarmLogsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AlarmLogs::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'task_id' => $this->faker->randomDigitNotNull,
            'adver_id' => $this->faker->randomDigitNotNull,
            'adver_name' => $this->faker->word,
            'is_valid' => $this->faker->randomDigitNotNull,
            'shop_id' => $this->faker->randomDigitNotNull,
            'ad_name' => $this->faker->word,
            'punish_rule' => $this->faker->word,
            'exec_result' => $this->faker->word,
            'created_at' => $this->faker->word,
            'type' => $this->faker->word,
            'updated_at' => $this->faker->word,
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
