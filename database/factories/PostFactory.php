<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostActivity;
use App\Models\PostFeeling;
use App\Models\PostGif;
use App\Models\PostTheme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionMethod;

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
            'audience_type'=>Arr::random(Post::AUDIENCE_TYPE),
            'user_id'=>User::inRandomOrder()->first()->id,
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
                'text'=>$attributes['text'] . ' ' . join(' ', PostFeeling::inRandomOrder()->pluck('icon')->toArray()),
            ];
        });
    }

    public function withTagged()
    {
        $this->withTagged = true;
        return $this;
    }

    public function forUser($user_id = null)
    {
        return $this->state(function ($attributes) use ($user_id) {
            return ['user_id'=>$user_id];
        });
    }

    public function atRandomCreatedAt()
    {
        return $this->state(function ($attributes) {
            return ['created_at'=>$this->faker->dateTimeBetween('-1 years', 'now')];
        });
    }

    public function chainFeatures()
    {
        $featuresList = self::extractFeatures();

        //i am using $obj = $this , because $this is immutable when we call $this->state() on it
        //so by calling for example $this->withTheme() .. it will call $this->withState()
        //which is going to create totally_new_object so i needed to track the recent object created
        //by using a variable
        $obj = $this;
        for ($i=0;$i<=array_rand($featuresList);$i++) {
            $obj = app()->call([$this,Arr::random($featuresList)->getName()]);
        }
        return $obj;
    }

    /**
    * extract features by scanning class and return public methods which start with 'with'
    */
    protected static function extractFeatures()
    {
        return collect(
            (new ReflectionClass(self::class))
            ->getMethods(ReflectionMethod::IS_PUBLIC)
        )
        ->filter(function (ReflectionMethod $reflectionMethod) {
            return str_contains($reflectionMethod->getName(), 'with');
        })
        ->toArray();
    }
}
