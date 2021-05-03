<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    /** @test */
    public function user_can_fetch_feed_for_friends_public_post()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->user->friendsOfMine()->attach($user1->id);
        $this->user->friendOf()->attach($user2->id);

        $post1 = Post::factory()->forUser($user1)->create(['audience_type'=>'public']);
        $post2 = Post::factory()->forUser($user2)->create(['audience_type'=>'public']);
        
        $response = $this->getJson('/api/posts/feed');
        $response->assertSee($post1->text);
        $response->assertSee($post2->text);
    }

    /** @test */
    public function user_dont_fetch_feed_for_friends_onlyme_post()
    {
        $user1 = User::factory()->create();
        $this->user->friendsOfMine()->attach($user1->id);
        $post = Post::factory()->forUser($user1)->create(['audience_type'=>'only_me']);
        $response = $this->getJson('/api/posts/feed');
        $response->assertDontSee($post->text);
    }
}
