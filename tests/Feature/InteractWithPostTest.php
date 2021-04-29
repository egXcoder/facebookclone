<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class InteractWithPostTest extends TestCase
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
    public function logged_user_can_like_post()
    {
        $post = Post::factory()->create();
        $this->post("api/posts/$post->id/like", ['type'=>'haha']);
        $like = $post->likes->first(function ($like) {
            return $like->user_id == auth('api')->user()->id;
        });

        $this->assertEquals('haha', $like->type);
    }

    /** @test */
    public function logged_user_cant_like_post_with_invalid_type()
    {
        $post = Post::factory()->create();
        $response = $this->postJson("api/posts/$post->id/like", ['type'=>'xyz']);
        $like = $post->likes->first(function ($like) {
            return $like->user_id == auth('api')->user()->id;
        });

        $this->assertNull($like);
        $response->assertSee('The given data was invalid');
        $response->assertSee('type is invalid');
    }

    /** @test */
    public function logged_user_can_unlike_post()
    {
        $post = Post::factory()->create();
        Like::factory()->forPost($post->id)->forUser($this->user)->create();
        $this->assertCount(1,$post->likes);

        $this->postJson("api/posts/$post->id/unlike");
        $post->refresh();
        $this->assertCount(0,$post->likes);
    }

    /** @test */
    public function logged_user_can_comment_post()
    {
        $post = Post::factory()->create();
        $this->postJson("api/posts/$post->id/comments",['text'=>'hello']);
        
        $this->assertEquals('hello',$post->comments->first()->text);
    }
}
