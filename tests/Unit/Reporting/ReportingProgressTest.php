<?php

namespace Tests\Unit\Reporting;

use Tests\TestCase;
use App\Models\Reporting;
use App\Models\ReportingReason;
use App\Models\VictimRequirement;
use App\Models\DisabilityType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingProgressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_pp_03()
    {
        $user = User::factory()->tamuSatgas()->create();

        $reporting = Reporting::factory()->create([
            'reporter_id' => $user->id,
        ]);

        $reporting->disabilityType()->attach(DisabilityType::factory()->count(2)->create()->pluck('id'));
        $reporting->reportingReason()->attach(ReportingReason::factory()->count(2)->create()->pluck('id'));
        $reporting->victimRequirement()->attach(VictimRequirement::factory()->count(2)->create()->pluck('id'));

        // Relations
        $reporting = Reporting::with([
            'reportingUser',
            'reportingReason',
            'reportedStatus',
            'disabilityType',
            'victimRequirement',
        ])->find($reporting->id);

        // Send progress
        $this->post(route('reportings.progress.create', ['id' => $reporting->id]), [
            'note' => 'Proses ya',
        ]);

        // Cek di halaman
        $response = $this->get(route('reportings.progress', ['reporting' => $reporting->id]));

        // Expected Result
        $response->assertStatus(200);
        $response->assertSee('1');
    }

    public function test_pp_02()
    {
        $user = User::factory()->tamuSatgas()->create();

        $reporting = Reporting::factory()->create([
            'reporter_id' => $user->id,
        ]);

        $reporting->disabilityType()->attach(DisabilityType::factory()->count(2)->create()->pluck('id'));
        $reporting->reportingReason()->attach(ReportingReason::factory()->count(2)->create()->pluck('id'));
        $reporting->victimRequirement()->attach(VictimRequirement::factory()->count(2)->create()->pluck('id'));

        // Relations
        $reporting = Reporting::with([
            'reportingUser',
            'reportingReason',
            'reportedStatus',
            'disabilityType',
            'victimRequirement',
        ])->find($reporting->id);

        // Send progress
        $response = $this->post(route('reportings.progress.create'), [
            'reporting_id' => $reporting->id,
            'note' => 'Kasus sedang ditangani oleh Satgas PPKS Poliwangi sesuai dengan kebutuhan korban',
        ]);

        // Expected Result
        $response->assertStatus(302);
        $response->assertRedirect(route('reportings.progress', ['reporting' => $reporting->id]));
    }

    public function test_pp_01()
    {
        $user = User::factory()->tamuSatgas()->create();

        $reporting = Reporting::factory()->create([
            'reporter_id' => $user->id,
        ]);

        $reporting->disabilityType()->attach(DisabilityType::factory()->count(2)->create()->pluck('id'));
        $reporting->reportingReason()->attach(ReportingReason::factory()->count(2)->create()->pluck('id'));
        $reporting->victimRequirement()->attach(VictimRequirement::factory()->count(2)->create()->pluck('id'));

        // Relations
        $reporting = Reporting::with([
            'reportingUser',
            'reportingReason',
            'reportedStatus',
            'disabilityType',
            'victimRequirement',
        ])->find($reporting->id);

        $response = $this->get(route('reportings.progress', ['reporting' => $reporting->id]));

        // Expected Result
        $response->assertStatus(200);

        $response->assertSee('Progress Pengaduan');
        $response->assertSee('Catatan Progress');
        $response->assertSee('Tambahkan');
        $response->assertSee('Belum ada progress');
    }
}
