<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $sender) {
            foreach (User::all() as $receiver) {
                Message::factory(20)->withSender($sender)->withReceiver($receiver)->create();
            }
        }
    }
}
