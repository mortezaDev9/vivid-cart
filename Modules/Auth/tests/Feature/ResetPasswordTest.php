<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_displays_the_reset_password_view():void
    {
        $response = $this->get(route('reset-password.form', [
            'token' => 'dummy-token',
            'email' => $this->user->email
        ]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('auth::reset-password');
        $response->assertViewHas('token', 'dummy-token');
    }

    #[Test]
    public function it_can_reset_password():void
    {
        Event::fake();

        $response = $this->post(route('reset-password', [
            'token'                 => $this->createPasswordResetToken(),
            'email'                 => $this->user->email,
            'password'              => 'new-password',
            'password_confirmation' => 'new-password'
        ]));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('login.form');
        $response->assertSessionHas('success', __('Your password has been reset successfully.'));

        $this->user->refresh();

        $this->assertTrue(Hash::check('new-password', $this->user->password));
        $this->assertNotEquals($this->user->remeberToken, $this->user->getOriginal('remember_token'));

        Event::assertDispatched(PasswordReset::class);
    }

    #[Test]
    public function it_fails_to_reset_password_with_wrong_email(): void
    {
        $response = $this->post(route('reset-password', [
            'token'                 => $this->createPasswordResetToken(),
            'email'                 => 'wrong@gmail.com',
            'password'              => 'new-password',
            'password_confirmation' => 'new-password'
        ]));

        $response->assertSessionHasErrors(['email' => __('Invalid token or email')]);
        $this->assertFalse(Hash::check('new-password', $this->user->fresh()->password));
    }

    #[Test]
    public function it_fails_to_reset_password_with_invalid_token(): void
    {
        $response = $this->post(route('reset-password', [
            'token'                 => 'invalid-token',
            'email'                 => $this->user->email,
            'password'              => 'new-password',
            'password_confirmation' => 'new-password',
        ]));

        $response->assertSessionHasErrors(['email' => __('Invalid token or email')]);
        $this->assertFalse(Hash::check('new-password', $this->user->fresh()->password));
    }

    private function createPasswordResetToken(): string
    {
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        return $token;
    }
}
