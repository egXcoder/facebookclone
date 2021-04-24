<?php

namespace Tests\Feature;

use App\Listeners\AddUserToRecentLogins;
use App\Listeners\RegenerateRecentLogin;
use App\Listeners\SetApiTokenForAuthenticatedUser;
use App\Models\RecentLogin;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Faker\Generator;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password'=>Hash::make(123)
        ]);
    }

    /** @test */
    public function new_user_doesnt_have_api_token()
    {
        $this->assertNull($this->user->api_token);
    }

    /** @test */
    public function user_login_will_trigger_the_login_event()
    {
        $this->expectsEvents(Login::class);

        $this->post('/login', [
            'email'=>$this->user->email,
            'password'=>'123'
        ]);
    }

    /** @test */
    public function user_login_will_trigger_setApiToken_and_regenerateRecentLogin()
    {
        $setApiTokenListener = $this->replaceEventListenerWithMock(SetApiTokenForAuthenticatedUser::class);
        $addUserRecentLogin = $this->replaceEventListenerWithMock(RegenerateRecentLogin::class);

        $this->post('/login', [
            'email'=>$this->user->email,
            'password'=>'123'
        ]);

        $setApiTokenListener->shouldReceive('handle');
        $addUserRecentLogin->shouldReceive('handle');
    }

    protected function replaceEventListenerWithMock($eventListenerClass)
    {
        $instance = Mockery::spy($eventListenerClass);
        app()->instance($eventListenerClass, $instance);
        return $instance;
    }

    /** @test */
    public function user_login_will_set_api_token()
    {   
        $this->post('/login', [
            'email'=>$this->user->email,
            'password'=>'123'
        ]);

        $this->user->refresh();
        $this->assertIsString($this->user->api_token);
    }

    /** @test */
    public function user_login_will_insert_recent_login_record()
    {
        $this->post('/login', [
            'email'=>$this->user->email,
            'password'=>'123',
            'user_agent'=>app(Generator::class)->userAgent,
            'fingerprint'=>'123456'
        ]);

        $this->assertEquals('123456', RecentLogin::first()->fingerprint);
    }

    /** @test */
    public function user_login_sets_recent_login_in_cookie()
    {
        $response = $this->post('/login', [
            'email'=>$this->user->email,
            'password'=>'123',
            'user_agent'=>app(Generator::class)->userAgent,
            'fingerprint'=>'123456'
        ]);

        $response->assertCookie('recent_logins', json_encode([$this->user->id=>RecentLogin::first()->token]), false);
    }

    /** @test */
    public function browser_can_create_multiple_recent_logins()
    {
        $user1 = User::factory()->create(['password'=>Hash::make(123)]);
        $user2 = User::factory()->create(['password'=>Hash::make(123)]);

        $loginInformation = [
            'password'=>'123',
            'user_agent'=>app(Generator::class)->userAgent,
            'fingerprint'=>'123456'
        ];
        
        $this->post('/login', array_merge($loginInformation, ['email'=>$user1->email]));
        $this->post('/logout');
        $cookie = ['recent_logins'=>json_encode([$user1->id=>RecentLogin::where('user_id', $user1->id)->first()->token])];
        $response = $this->call('POST', '/login', array_merge($loginInformation, ['email'=>$user2->email]), $cookie);
        $this->post('/logout');

        $response->assertCookie('recent_logins', json_encode([
            $user1->id => RecentLogin::where('user_id', $user1->id)->first()->token,
            $user2->id => RecentLogin::where('user_id', $user2->id)->first()->token,
        ]), false);
    }

    /** @test */
    public function user_can_only_have_only_one_valid_recent_login_on_relogin()
    {
        $loginInformation = [
            'email'=>$this->user->email,
            'password'=>'123',
            'user_agent'=>app(Generator::class)->userAgent,
            'fingerprint'=>'123456'
        ];
        
        $this->post('/login', $loginInformation);
        $this->post('/logout');
        $this->post('/login', $loginInformation);

        $this->assertCount(2, RecentLogin::all());
        $this->assertTrue(RecentLogin::latest('id')->first()->isValidToShow());
        $this->assertFalse(RecentLogin::oldest('id')->first()->isValidToShow());
    }

    /** @test */
    public function user_can_login_with_recent_login()
    {
        $loginInformation = [
            'email'=>$this->user->email,
            'password'=>'123',
            'user_agent'=>app(Generator::class)->userAgent,
            'fingerprint'=>'123456'
        ];
        
        $this->post('/login', $loginInformation);
        $this->post('logout');

        $recent = RecentLogin::first();
        $response = $this->post("/recent-logins/$recent->id/login", [
            'token'=>$recent->token,
            'user_agent'=>app(Generator::class)->userAgent,
            'fingerprint'=>$recent->fingerprint
        ]);

        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertCount(2, RecentLogin::all());
        $this->assertTrue(RecentLogin::find(1)->is_deleted);
        $this->assertFalse(RecentLogin::find(2)->is_deleted);
    }

    /** @test */
    public function when_user_use_recent_login_it_will_generate_new_record()
    {
        $loginInformation = [
            'email'=>$this->user->email,
            'password'=>'123',
            'user_agent'=>app(Generator::class)->userAgent,
            'fingerprint'=>'123456'
        ];
        
        $this->post('/login', $loginInformation);
        $this->post('logout');

        $recent = RecentLogin::first();
        $this->post("/recent-logins/$recent->id/login", [
            'token'=>$recent->token,
            'user_agent'=>app(Generator::class)->userAgent,
            'fingerprint'=>$recent->fingerprint
        ]);
        $this->assertCount(2, RecentLogin::all());
        $this->assertTrue(RecentLogin::find(1)->is_deleted);
        $this->assertFalse(RecentLogin::find(2)->is_deleted);
    }

    /**@test */
    // public function when_session_finish()
    // {
    //     $loginInformation = [
    //         'email'=>$this->user->email,
    //         'password'=>'123',
    //         'user_agent'=>app(Generator::class)->userAgent,
    //         'fingerprint'=>'123456'
    //     ];
        
    //     $this->post('/login', $loginInformation);
    //     $this->regenerate
    // }
}
