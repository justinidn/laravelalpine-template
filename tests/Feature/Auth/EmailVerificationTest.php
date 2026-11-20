<?php

namespace Tests\Feature\Auth;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $this->createUnverifiedUser();

        $response = $this->get('/verify-email');

        $response->assertOk()
            ->assertSee('Konfirmasi Email Anda')
            ->assertSee('Kirim ulang tautan konfirmasi');
    }

    public function test_verification_email_contains_a_confirmation_button(): void
    {
        $user = $this->createUnverifiedUser();

        $email = (new VerifyEmail)->toMail($user);
        $html = (string) $email->render();

        $this->assertStringContainsString('Konfirmasi Email', $html);
        $this->assertStringContainsString('/verify-email/'.$user->id.'/'.sha1($user->email), $html);
    }

    public function test_signed_email_link_can_verify_a_user_before_login(): void
    {
        $user = $this->createUnverifiedUser();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = $this->createUnverifiedUser();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->get($verificationUrl)->assertForbidden();

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_guest_can_request_another_verification_email(): void
    {
        $user = $this->createUnverifiedUser();
        Notification::fake();

        $response = $this->from('/verify-email')->post('/email/verification-notification', [
            'email' => $user->email,
        ]);

        $response->assertRedirect('/verify-email')
            ->assertSessionHas('status', 'verification-link-sent');
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_unverified_users_cannot_access_application_pages(): void
    {
        $user = $this->createUnverifiedUser();

        $this->actingAs($user)
            ->get('/departments')
            ->assertRedirect(route('verification.notice'));
    }

    private function createUnverifiedUser(): User
    {
        $department = Department::query()->create([
            'department_name' => 'Test Department',
            'department_alias' => 'TEST',
        ]);

        return User::factory()
            ->unverified()
            ->create(['department_id' => $department->id]);
    }
}
