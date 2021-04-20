<?php

namespace Database\Factories;

use App\Models\PostTheme;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostThemeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostTheme::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $random = rand(0,1000);
        return [
            'color'=>$this->faker->hexColor,
            'background_url'=>"https://picsum.photos/id/$random/600/600"
        ];
    }
}
