<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostActivity;
use App\Models\PostFeeling;
use App\Models\PostTheme;
use App\Models\User;
use Database\Seeders\PostActivitySeeder;
use Database\Seeders\PostFeelingSeeder;
use Database\Seeders\PostGifSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SavePostTest extends TestCase
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
    public function save_post_with_only_text()
    {
        $response = $this->postJson('/api/posts', [
            'text'=>'hello world',
            'audience_type'=>'public'
        ]);
        $this->assertEquals("hello world", Post::first()->text);
        $response->assertSee('success');
    }

    /** @test */
    public function save_post_with_theme()
    {
        PostTheme::factory()->create();

        $response = $this->postJson('/api/posts', [
            'text'=>'hello world',
            'audience_type'=>'public',
            'theme_id'=>1
        ]);

        $this->assertEquals(1, Post::first()->theme_id);
        $response->assertSee('success');
    }

    /** @test */
    public function save_post_with_feeling()
    {
       (new PostFeelingSeeder)->run();

        $this->postJson('/api/posts', [
            'text'=>'hello world',
            'audience_type'=>'public',
            'feeling_id'=>1
        ]);

        $this->assertEquals(1, Post::first()->doingable_id);
        $this->assertEquals(PostFeeling::class, Post::first()->doingable_type);
    }

    /** @test */
    public function save_post_with_activity()
    {
        (new PostActivitySeeder)->run();

        $this->postJson('/api/posts', [
            'text'=>'hello world',
            'audience_type'=>'public',
            'activity_id'=>1
        ]);

        $this->assertEquals(1, Post::first()->doingable_id);
        $this->assertEquals(PostActivity::class, Post::first()->doingable_type);
    }

    /** @test */
    public function save_post_with_tagged_friends()
    {
        $tagged = $this->user->friends()->pluck('friend_id')->toArray();
        $this->postJson('/api/posts', [
            'text'=>'hello world',
            'audience_type'=>'public',
            'tagged'=>$tagged
        ]);
        
        $this->assertEquals(
            $tagged,
            Post::first()->tagged_users->pluck('id')->toArray()
        );
    }

    /** @test */
    public function save_post_with_gifs()
    {
        (new PostGifSeeder)->run();
        
        $this->postJson('/api/posts', [
            'text'=>'hello world',
            'audience_type'=>'public',
            'gif_id'=>1
        ]);
        
        $this->assertEquals(1, Post::first()->gif_id);
    }

    /** @test */
    public function post_cant_have_gif_and_theme_in_same_time()
    {
        $response = $this->postJson('/api/posts', [
            'text'=>'hello world',
            'audience_type'=>'public',
            'gif_id'=>1,
            'theme_id'=>1
        ]);

        $response->assertSee('The given data was invalid.');
        $response->assertSee('prohibited');
    }

    /** @test */
    public function post_cant_have_feeling_and_activity_in_same_time()
    {
        $response = $this->postJson('/api/posts', [
            'text'=>'hello world',
            'audience_type'=>'public',
            'feeling_id'=>1,
            'activity_id'=>1
        ]);

        $response->assertSee('The given data was invalid.');
        $response->assertSee('prohibited');
    }
}
