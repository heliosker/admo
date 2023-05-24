<?php

namespace Database\Factories;

use App\Models\Ads;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ads::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ad_id' => $this->faker->word,
        'ad_create_time' => $this->faker->word,
        'ad_modify_time' => $this->faker->word,
        'lab_ad_type' => $this->faker->word,
        'marketing_goal' => $this->faker->word,
        'marketing_scene' => $this->faker->word,
        'name' => $this->faker->word,
        'status' => $this->faker->word,
        'opt_status' => $this->faker->word,
        'aweme_id' => $this->faker->word,
        'aweme_name' => $this->faker->word,
        'aweme_show_id' => $this->faker->word,
        'aweme_avatar' => $this->faker->word,
        'deep_external_action' => $this->faker->word,
        'deep_bid_type' => $this->faker->word,
        'roi_goal' => $this->faker->word,
        'cpa_bid' => $this->faker->word,
        'start_time' => $this->faker->word,
        'end_time' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
