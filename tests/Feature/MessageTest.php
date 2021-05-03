<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MessageTest extends TestCase
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
    public function logged_user_can_fetch_all_messages()
    {
        $chatWith = User::factory()->create();
        Message::factory(2)->withSender($this->user)->withReceiver($chatWith)->create();
        Message::factory(2)->withSender($chatWith)->withReceiver($this->user)->create();

        $response = $this->getJson("/api/user/$chatWith->id/messages");
        $this->assertCount(4, $response->original);
    }

    /** @test */
    public function sending_message_via_empty_text_is_not_allowed()
    {
        $chatWith = User::factory()->create();
        $response = $this->postJson("/api/user/$chatWith->id/messages",[]);
        $response->assertSee('error');
    }

    /** @test */
    public function logged_user_can_send_message()
    {
        $chatWith = User::factory()->create();
        $response = $this->postJson("/api/user/$chatWith->id/messages", ['text'=>'hello world']);
        $response->assertSee('hello world');
    }
}
