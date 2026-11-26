<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FoodLoopFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeReport(User $provider, array $overrides = []): Report
    {
        $partner = Partner::create(['name' => 'Panti Uji', 'type' => 'layak', 'lat' => -6.2, 'lng' => 106.8]);

        return Report::create(array_merge([
            'provider_name' => $provider->organization, 'provider_id' => $provider->id, 'title' => 'Nasi kotak',
            'category' => 'layak', 'portions' => 10, 'weight_kg' => 4, 'expires_at' => now()->addHours(3),
            'lat' => -6.21, 'lng' => 106.81, 'partner_id' => $partner->id,
        ], $overrides));
    }

    public function test_public_pages_render(): void
    {
        $this->makeReport(User::factory()->provider()->create());

        $this->get(route('home'))->assertOk()->assertSee('Selamatkan Sebelum Terlambat')->assertSee('Simulator Dampak');
        $this->get(route('map'))->assertOk();
        $this->get(route('impact'))->assertOk()->assertSee('Makanan Tersalurkan per Bulan');
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
        $this->get(route('about'))->assertOk()->assertSee('Kontribusi pada SDGs')->assertSee('Panduan uji coba')->assertSee('Pertanyaan yang Sering Diajukan');
    }

    public function test_background_media_uses_video_with_pause_control_only_when_file_exists(): void
    {
        $name = 'zz-test-'.uniqid();
        $this->blade('<x-bg-media :name="$name" />', ['name' => $name])
            ->assertDontSee('<video', false)
            ->assertSee('<svg', false);

        $dir = public_path('videos');
        $createdDir = ! is_dir($dir);
        if ($createdDir) {
            mkdir($dir);
        }
        $file = "{$dir}/{$name}.mp4";
        file_put_contents($file, 'dummy');

        try {
            $this->blade('<x-bg-media :name="$name" />', ['name' => $name])
                ->assertSee('<video', false)
                ->assertSee("videos/{$name}.mp4", false)
                ->assertSee('bgVideo', false);
        } finally {
            unlink($file);
            if ($createdDir) {
                rmdir($dir);
            }
        }
    }

    public function test_registration_accepts_optional_avatar_and_location(): void
    {
        Storage::fake('public');

        $this->get(route('register'))->assertOk()->assertSee('Gunakan lokasi saat ini')->assertSee('Mitra Pengolah Organik');

        $this->post(route('register'), [
            'role' => 'recipient', 'name' => 'Sari', 'organization' => 'Dapur Umum', 'email' => 'sari@example.test',
            'password' => 'rahasia', 'password_confirmation' => 'rahasia', 'lat' => -6.3, 'lng' => 106.8,
            'avatar' => UploadedFile::fake()->image('sari.png', 200, 200),
        ])->assertRedirect(route('map'));

        $user = User::where('email', 'sari@example.test')->first();
        $this->assertEqualsWithDelta(-6.3, $user->lat, 0.0001);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_guest_is_redirected_from_profile(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
    }

    public function test_user_can_update_profile_location_and_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->provider()->create();

        $this->actingAs($user)->get(route('profile.edit'))->assertOk()->assertSee('Gunakan lokasi saat ini');

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Restoran Baru', 'organization' => 'Resto Baru', 'phone' => '081211112222',
            'email' => $user->email, 'lat' => -6.25, 'lng' => 106.9,
            'avatar' => UploadedFile::fake()->image('foto.jpg', 300, 300),
        ])->assertRedirect(route('profile.edit'))->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('Restoran Baru', $user->name);
        $this->assertEqualsWithDelta(-6.25, $user->lat, 0.0001);
        $this->assertSame('provider', $user->role);
        Storage::disk('public')->assertExists($user->avatar_path);

        $this->actingAs($user)->get(route('report.create'))->assertOk()->assertSee('lat:-6.25', false);
    }

    public function test_profile_email_must_be_unique_and_role_cannot_change(): void
    {
        $other = User::factory()->recipient()->create();
        $user = User::factory()->provider()->create();

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name, 'organization' => 'X', 'email' => $other->email, 'role' => 'recipient',
        ])->assertSessionHasErrors('email');

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name, 'organization' => 'X', 'email' => $user->email, 'role' => 'recipient',
        ])->assertSessionHasNoErrors();
        $this->assertSame('provider', $user->fresh()->role);
    }

    public function test_login_prefills_only_demo_accounts(): void
    {
        $this->get(route('login', ['demo' => 'bsf@foodloop.test']))->assertOk()->assertSee('Akun demo sudah terisi');
        $this->get(route('login', ['demo' => 'someone@example.com']))->assertOk()->assertDontSee('Akun demo sudah terisi');
    }

    public function test_map_pins_endpoint_hides_contact_until_claimed(): void
    {
        $report = $this->makeReport(User::factory()->provider()->create());

        $this->getJson(route('map.pins'))->assertOk()
            ->assertJsonPath('pins.0.id', $report->id)
            ->assertJsonPath('pins.0.contact', null);

        $recipient = User::factory()->recipient()->create();
        $report->update(['status' => 'claimed', 'recipient_id' => $recipient->id]);

        $this->actingAs($recipient)->getJson(route('map.pins'))
            ->assertJsonPath('pins.0.claimedByMe', true)
            ->assertJsonPath('pins.0.contact', fn ($url) => str_starts_with($url, 'https://wa.me/6281234567890'));
    }

    public function test_recipient_can_claim_and_second_claim_is_rejected(): void
    {
        $report = $this->makeReport(User::factory()->provider()->create());
        $first = User::factory()->recipient()->create();
        $second = User::factory()->recipient()->create();

        $this->actingAs($first)->post(route('report.act', [$report, 'claim']))
            ->assertRedirect(route('status', ['id' => $report->id]));
        $this->assertSame($first->id, $report->fresh()->recipient_id);

        $this->actingAs($second)->post(route('report.act', [$report, 'claim']))->assertSessionHasErrors('claim');
        $this->assertSame($first->id, $report->fresh()->recipient_id);
    }

    public function test_organic_waste_is_claimable_only_by_processor(): void
    {
        $report = $this->makeReport(User::factory()->provider()->create(), ['category' => 'organik']);

        $this->actingAs(User::factory()->recipient()->create())->post(route('report.act', [$report, 'claim']))->assertForbidden();

        $processor = User::factory()->create(['role' => 'processor', 'organization' => 'BSF Farm']);
        $this->actingAs($processor)->post(route('report.act', [$report, 'claim']))->assertRedirect(route('status', ['id' => $report->id]));
        $this->assertSame($processor->id, $report->fresh()->recipient_id);
    }

    public function test_processor_cannot_claim_food_fit_for_people(): void
    {
        $report = $this->makeReport(User::factory()->provider()->create());

        $this->actingAs(User::factory()->create(['role' => 'processor']))->post(route('report.act', [$report, 'claim']))->assertForbidden();
    }

    public function test_user_can_register_as_processor(): void
    {
        $this->post(route('register'), [
            'role' => 'processor', 'name' => 'Rina', 'organization' => 'Rumah Kompos', 'email' => 'rina@example.test',
            'password' => 'rahasia', 'password_confirmation' => 'rahasia',
        ])->assertRedirect(route('map'));

        $this->assertSame('processor', User::where('email', 'rina@example.test')->value('role'));
    }

    public function test_recipient_can_release_claim(): void
    {
        $recipient = User::factory()->recipient()->create();
        $report = $this->makeReport(User::factory()->provider()->create(), ['status' => 'claimed', 'recipient_id' => $recipient->id, 'volunteer_name' => $recipient->name]);

        $this->actingAs(User::factory()->recipient()->create())->post(route('report.act', [$report, 'release']))->assertForbidden();

        $this->actingAs($recipient)->post(route('report.act', [$report, 'release']))->assertRedirect(route('map'));
        $report->refresh();
        $this->assertSame('waiting', $report->status);
        $this->assertNull($report->recipient_id);
    }

    public function test_completion_stores_thank_note_and_shows_it_to_provider(): void
    {
        Storage::fake('public');
        $provider = User::factory()->provider()->create();
        $recipient = User::factory()->recipient()->create();
        $report = $this->makeReport($provider, ['status' => 'on_the_way', 'recipient_id' => $recipient->id, 'volunteer_name' => $recipient->name]);

        $this->actingAs($recipient)->post(route('report.act', [$report, 'complete']), [
            'proof' => UploadedFile::fake()->image('bukti.jpg'), 'note' => 'Terima kasih banyak!',
        ])->assertRedirect(route('status', ['id' => $report->id]));

        $report->refresh();
        $this->assertSame('delivered', $report->status);
        $this->assertSame('Terima kasih banyak!', $report->thank_note);

        $this->actingAs($provider)->get(route('status', ['id' => $report->id]))->assertOk()->assertSee('Terima kasih banyak!')->assertSee('https://wa.me/6281298765432', false);
        $this->actingAs($provider)->get(route('history'))->assertOk()->assertSee('Langkah Pertama')->assertSee('Pemula Peduli');
    }

    public function test_whatsapp_url_normalizes_local_numbers(): void
    {
        $this->assertSame('https://wa.me/6281200000', (new User(['phone' => '0812-000-00']))->whatsappUrl());
        $this->assertNull((new User(['phone' => null]))->whatsappUrl());
    }
}
