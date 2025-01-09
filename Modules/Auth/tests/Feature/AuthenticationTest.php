<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password'       => 'password',
            'remember_token' => null,
        ]);
    }

    #[Test]
    public function it_displays_the_login_view(): void
    {
        $response = $this->get(route('login.form'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('auth::login');
    }

    #[Test]
    public function it_can_login(): void
    {
        $response = $this->post(route('login'), [
            'email'    => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('home');
        $this->assertAuthenticated();
    }

    #[Test]
    public function it_can_login_with_remember_me(): void
    {
        $this->post(route('login'), [
            'email'    => $this->user->email,
            'password' => 'password',
            'remember' => true,
        ]);

        // Logging out user by flushing session
        $this->flushSession();

        $response = $this->get(route('login.form'));

        $response->assertRedirectToRoute('home');
        $this->assertAuthenticatedAs($this->user);
        $this->assertNotNull($this->user->fresh()->remember_token);
    }

    #[Test]
    public function it_redirects_authenticated_users_from_login_view(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('login.form'));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('home');
    }

    #[Test]
    public function it_can_logout(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
