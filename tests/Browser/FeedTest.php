<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FeedTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp():void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function feed_will_show_top_ranked_posts()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/')->assertSee('facebook');
        });
        //given we have list of possible posts
        //when user goes to feed
        //he will see top 15 post in its rank, till he scroll more and this will load more
    }

    /** @test */
    public function feed_will_show_next_top_ranked_posts_on_scroll()
    {
        //given we have list of possible posts
        //when user goes to feed
        //he will see next top 15 post in its rank, when he scroll more
    }

    /** @test */
    public function feed_will_show_friends_posts()
    {
        //given a friend have new post
        //when user goes to feed
        //he will see friend post
    }

    // /** @test */
    public function feed_will_show_user_newly_created_post()
    {
        //given we have new user post
        //when user goes to feed
        //he will see the post
    }

    public function user_will_see_on_feed_the_highest_rank_posts_from_friends()
    {
        //given we have two friends and each has created new post
        //when user goes to feed
        // he will see the post with high rank first 
    }
}
