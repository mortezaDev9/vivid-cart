<?php

namespace Modules\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->user->cart()->create();
        $this->user->wishlist()->create();
    }

    #[Test]
    public function it_can_view_profile_page(): void
    {
        $response = $this->get(route('account.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('user::index');
    }

    #[Test]
    public function it_denies_access_to_profile_page_for_guest_users(): void
    {
        auth()->logout();

        $response = $this->get(route('account.index'));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('login');
    }

    #[Test]
    public function it_can_view_edit_profile_page(): void
    {
        $response = $this->get(route('account.profile.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('user::edit-profile');
    }

    #[Test]
    public function it_denies_access_to_edit_profile_page_for_guest_users(): void
    {
        auth()->logout();

        $response = $this->get(route('account.profile.edit'));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('login');
    }

    #[Test]
    public function it_can_update_profile(): void
    {
        $response = $this->put(route('account.profile.update'), [
            'username' => 'new_username',
            'email'    => 'newemail@example.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('account.index');

        $this->assertDatabaseHas('users', [
            'id'       => $this->user->id,
            'username' => 'new_username',
            'email'    => 'newemail@example.com',
        ]);
    }

    #[Test]
    public function it_cannot_update_profile_with_invalid_data(): void
    {
        User::factory()->create(['username' => 'non-unique-username']);
        $testCases = [
            'empty_username' => [
                'payload' => ['username' => '', 'email' => 'email@example.com'],
                'field'   => __('username'),
            ],
            'non_unique_username' => [
                'payload' => ['username' => 'non-unique-username', 'email' => 'email@example.com'],
                'field'   => __('username'),
            ],
            'invalid_email' => [
                'payload' => ['username' => 'valid_username', 'email' => 'invalid-email'],
                'field'   => __('email'),
            ],
            'empty_email' => [
                'payload' => ['username' => '', 'email' => 'invalid-email'],
                'field'   => __('email'),
            ],
        ];

        foreach ($testCases as $testCase => $data) {
            $response = $this->put(route('account.profile.update'), $data['payload']);

            $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect(url()->previous());
            $response->assertSessionHasErrors($data['field']);

            $this->assertDatabaseMissing('users', [
                'id'       => $this->user->id,
                'username' => $data['username'] ?? null,
                'email'    => $data['email'] ?? null,
            ]);
        }
    }

    #[Test]
    public function it_can_view_change_password_page(): void
    {
        $response = $this->get(route('account.password.change'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('user::change-password');
    }

    #[Test]
    public function it_denies_access_to_change_password_page_for_guest_users(): void
    {
        auth()->logout();

        $response = $this->get(route('account.password.change'));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('login');
    }

    #[Test]
    public function it_can_change_password(): void
    {
        $this->user->update([
            'password' => Hash::make('current_password'),
        ]);

        $response = $this->patch(route('account.password.update'), [
            'current_password'          => 'current_password',
            'new_password'              => 'new_secure_password',
            'new_password_confirmation' => 'new_secure_password',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('account.index');

        $this->assertTrue(Hash::check('new_secure_password', $this->user->refresh()->password));
    }

    #[Test]
    public function it_cannot_update_password_with_invalid_data(): void
    {
        $this->user->update([
            'password' => Hash::make('current_password'),
        ]);

        $testCases = [
            'incorrect_current_password' => [
                'payload' => [
                    'current_password'          => 'wrong_password',
                    'new_password'              => 'new_secure_password',
                    'new_password_confirmation' => 'new_secure_password',
                ],
                'field'   => __('current_password'),
            ],
            'same_as_current_password'   => [
                'payload' => [
                    'current_password'          => 'current_password',
                    'new_password'              => 'current_password',
                    'new_password_confirmation' => 'current_password',
                ],
                'field'   => __('new_password'),
            ],
            'mismatched_confirmation'    => [
                'payload' => [
                    'current_password'          => 'current_password',
                    'new_password'              => 'new_secure_password',
                    'new_password_confirmation' => 'mismatched_password',
                ],
                'field'   => __('new_password'),
            ],
        ];

        foreach ($testCases as $testCase => $data) {
            $response = $this->patch(route('account.password.update'), $data['payload']);

            $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect(url()->previous());
            $response->assertSessionHasErrors([$data['field']]);

            Log::info("Test Case: $testCase", [$data['field']]);

            $this->assertFalse(Hash::check(Hash::make($data['payload']['new_password']), $this->user->refresh()->password));
        }
    }

    #[Test]
    public function it_sees_nothing_updated_message_if_profile_has_no_changes(): void
    {
        $this->user->update([
            'username' => 'existing_username',
            'email'    => 'existing_email@example.com',
        ]);

        $response = $this->put(route('account.profile.update'), [
            'username' => 'existing_username',
            'email'    => 'existing_email@example.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('account.index');
        $response->assertSessionHas('info', __('Nothing updated'));
    }
}
