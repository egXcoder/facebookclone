<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserFriendSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->be($this->user,'api');
    }
    
    /** @test */
    public function user_can_fetch_friends()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->user->friends()->attach([$user1->id,$user2->id]);
        
        $response = $this->getJson('/api/user/friends');
        $this->assertCount(2,$response->original);
    }
}
