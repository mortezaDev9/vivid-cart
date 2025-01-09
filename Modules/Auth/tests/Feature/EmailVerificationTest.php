<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Modules\Auth\Notifications\VerifyEmail;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->unverified()->create();

        $this->actingAs($this->user);
    }

    #[Test]
   public function it_displays_the_email_verification_notice_view(): void
   {
       $response = $this->get(route('verification.notice'));

       $response->assertStatus(Response::HTTP_OK);
       $response->assertViewIs('auth::verify-email');
   }

    #[Test]
    public function it_verifies_the_user_email(): void
    {
        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinute(60),
            ['id' => $this->user->id, 'hash' => sha1($this->user->email)]
        );

        $response = $this->get($verificationUrl);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('home');
        $response->assertSessionHas('success', __('Your email has been verified.'));
        $this->assertTrue($this->user->fresh()->hasVerifiedEmail());

        Event::assertDispatched(Verified::class);
    }

    #[Test]
    public function it_resends_the_verification_email(): void
    {
        Notification::fake();

        $response = $this->post(route('verification.resend'));

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirect();
        $response->assertSessionHas('info', __('Verification link sent'));

        Notification::assertSentTo($this->user, VerifyEmail::class);
    }
}
