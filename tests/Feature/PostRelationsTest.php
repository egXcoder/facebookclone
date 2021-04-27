<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PostRelationsTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->be($this->user);
    }

    /** @test */
    public function post_can_have_many_likes()
    {
        $post = Post::factory()->create();
        Like::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create(['type'=>'like']);
        Like::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create(['type'=>'love']);
        Like::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create(['type'=>'care']);
        $this->assertCount(3, $post->likes);
    }

    /** @test */
    public function post_can_have_many_comments()
    {
        $post = Post::factory()->create();
        Comment::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create();
        Comment::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create();
        Comment::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create();
        $this->assertCount(3, $post->comments);
    }

    /** @test */
    public function comment_can_have_likes()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create();
        Like::factory()->forComment($comment->id)->forUser($this->user)->create(['type'=>'like']);
        Like::factory()->forComment($comment->id)->forUser($this->user)->create(['type'=>'love']);
        Like::factory()->forComment($comment->id)->forUser($this->user)->create(['type'=>'care']);
        $this->assertCount(3, $comment->likes);
    }

    /** @test */
    public function comment_can_have_comments()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create();
        Comment::factory()->forComment($comment->id)->forUser(User::factory()->create()->id)->create();
        Comment::factory()->forComment($comment->id)->forUser(User::factory()->create()->id)->create();
        Comment::factory()->forComment($comment->id)->forUser(User::factory()->create()->id)->create();
        $this->assertCount(3, $comment->comments);
    }
    
    /** @test */
    public function like_can_be_associated_with_post()
    {
        $post = Post::factory()->create();
        $like = Like::factory()->forPost($post->id)->forUser($this->user)->create(['type'=>'like']);
        $this->assertEquals(Post::class, $like->likeable_type);
        $this->assertEquals($post->id, $like->likeable->id);
    }

    /** @test */
    public function like_can_be_associated_with_comment()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create();
        $like = Like::factory()->forComment($comment->id)->forUser($this->user)->create(['type'=>'like']);
        $this->assertEquals(Comment::class, $like->likeable_type);
        $this->assertEquals($post->id, $like->likeable->id);
    }

    /** @test */
    public function comment_can_be_associated_with_post()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create();
        $this->assertEquals(Post::class, $comment->commentable_type);
        $this->assertEquals($comment->id, $comment->commentable->id);
    }

    /** @test */
    public function comment_can_be_associated_with_comment()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->forPost($post->id)->forUser(User::factory()->create()->id)->create();
        $comment_to_check = Comment::factory()->forComment($comment->id)->forUser(User::factory()->create()->id)->create();
        $this->assertEquals(Comment::class, $comment_to_check->commentable_type);
        $this->assertEquals($comment->id, $comment_to_check->commentable->id);
    }
}
