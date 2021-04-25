<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostActivity;
use App\Models\PostFeeling;
use App\Models\PostGif;
use App\Models\PostTheme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    protected $withTagged = false;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'text'=>$this->faker->paragraph(6),
            'audience_type'=>'public',
            'author_id'=>User::inRandomOrder()->first()->id
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            if ($this->withTagged) {
                $howManyFrinds = $post->user->friends()->count();
                $post->tagged_users()->sync(
                    $post->user->friends()->inRandomOrder()->take(0, $howManyFrinds)->get()
                );
            }
        });
    }

    public function withTheme()
    {
        return $this->state(function (array $attributes) {
            return [
                'theme_id'=>PostTheme::inRandomOrder()->first()->id,
                'gif_id'=>null
            ];
        });
    }

    public function withGif()
    {
        return $this->state(function (array $attributes) {
            return [
                'theme_id'=>null,
                'gif_id'=>PostGif::inRandomOrder()->first()->id
            ];
        });
    }

    public function withFeeling()
    {
        return $this->state(function (array $attributes) {
            return [
                'doingable_type'=>PostFeeling::class,
                'doingable_id'=>PostFeeling::inRandomOrder()->first()->id
            ];
        });
    }

    public function withActivity()
    {
        return $this->state(function (array $attributes) {
            return [
                'doingable_type'=>PostActivity::class,
                'doingable_id'=>PostActivity::inRandomOrder()->first()->id
            ];
        });
    }

    public function withEmoji()
    {
        return $this->state(function (array $attributes) {
            return [
                'text'=>$attributes['text'] + join(' ', PostFeeling::inRandomOrder()->pluck('icon')->toArray()),
            ];
        });
    }

    public function withTagged()
    {
        $this->withTagged = true;
        return $this;
    }
}
