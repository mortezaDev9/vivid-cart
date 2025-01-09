<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Modules\Auth\Notifications\ResetPassword;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_displays_the_forgot_password_view(): void
    {
        $response = $this->get(route('forgot-password.form'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('auth::forgot-password');
    }

    #[Test]
    public function it_sends_a_reset_password_email_if_the_email_is_a_valid_email(): void
    {
        Notification::fake();

        $response = $this->post(route('forgot-password.email'), [
            'email' => $this->user->email
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHas('info', __('A password reset link has been sent to your email address.'));

        Notification::assertSentTo($this->user, ResetPassword::class);
    }

    #[Test]
    public function it_fails_validation_when_email_is_missing(): void
    {
        $response = $this->post(route('forgot-password.email'));

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors(['email' => __('The email field is required.')]);
    }

    #[Test]
    public function it_fails_validation_when_email_is_invalid(): void
    {
        $response = $this->post(route('forgot-password.email'), [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors(['email' => __('The email field must be a valid email address.')]);
    }
}
