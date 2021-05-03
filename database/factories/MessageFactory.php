<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender_id'=> User::inRandomOrder()->first()->id,
            'receiver_id'=> User::inRandomOrder()->first()->id,
            'text'=>$this->faker->sentence,
            'created_at'=>$this->faker->dateTimeBetween('-3 day')
        ];
    }

    public function withSender(User $user)
    {
        return $this->state(function ($attributes) use ($user) {
            return [
                'sender_id'=>$user->id
            ];
        });
    }

    public function withReceiver(User $user)
    {
        return $this->state(function ($attributes) use ($user) {
            return [
                'receiver_id'=>$user->id
            ];
        });
    }
}
