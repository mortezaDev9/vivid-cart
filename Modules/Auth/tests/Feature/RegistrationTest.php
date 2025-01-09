<?php

namespace Modules\Auth\Tests\Feature;

use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Cart\Models\Cart;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_register_view(): void
    {
        $response = $this->get(route('register.form'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('auth::register');
    }

    #[Test]
    public function it_registers_a_user_successfully(): void
    {
        Event::fake();

        $response = $this->post(route('register'), [
            'username'              => 'Morteza',
            'slug'                  => 'morteza',
            'email'                 => 'morteza@gmail.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'agreement'             => 1,
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('verification.notice');

        $user = User::whereEmail('morteza@gmail.com')->first();

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'username' => 'Morteza',
            'email'    => 'morteza@gmail.com',
        ]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
        ]);

        Event::assertDispatched(Registered::class);
    }

    #[Test]
    public function it_redirects_authenticated_users_from_register_view(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('register.form'));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('home');
    }
}
