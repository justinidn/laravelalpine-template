<?php

namespace Tests\Feature\Auth;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $department = $this->createDepartment();

        $response = $this->get('/register');

        $response->assertOk()
            ->assertSee('Buat Akun Baru')
            ->assertSee('Alamat Email')
            ->assertSee('Konfirmasi Kata Sandi')
            ->assertSee($department->department_name);
    }

    public function test_new_users_can_register(): void
    {
        $department = $this->createDepartment();
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'nrk' => '1234567',
            'department_id' => $department->id,
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::query()->where('email', 'test@example.com')->firstOrFail();

        $this->assertGuest();
        $this->assertTrue($user->hasPermissionTo('system.login'));
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'department_id' => $department->id,
            'nrk' => '1234567',
            'email' => 'test@example.com',
        ]);
        $response->assertRedirect(route('verification.notice', absolute: false));
        Notification::assertSentTo($user, VerifyEmail::class);
        $this->assertDatabaseMissing('activity_log', [
            'description' => 'User logged in',
            'subject_id' => $user->id,
            'subject_type' => User::class,
        ]);

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->get($verificationUrl)->assertRedirect(route('login'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('activity_log', [
            'description' => 'User logged in',
            'subject_id' => $user->id,
            'subject_type' => User::class,
        ]);
    }

    public function test_registration_rejects_inactive_departments(): void
    {
        $department = $this->createDepartment(false);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'nrk' => '1234567',
            'department_id' => $department->id,
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/register')
            ->assertSessionHasErrors('department_id');
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com',
        ]);
        $this->assertGuest();
    }

    private function createDepartment(bool $isActive = true): Department
    {
        return Department::query()->create([
            'department_alias' => 'TEST',
            'department_name' => 'Test Department',
            'is_active' => $isActive,
        ]);
    }
}
