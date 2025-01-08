<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Reporting;
use App\Models\ReportingReason;
use App\Models\VictimRequirement;
use App\Models\DisabilityType;

class PeriodReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        // Masuk sebagai Tamu Satgas
        $this->actingAs(User::factory()->tamuSatgas()->create());
    }

    public function test_pr_11()
    {
        $response = $this->get('/laporan');

        $response->assertStatus(403);
    }

    public function test_pr_10()
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

        $response = $this->get('/laporan?year=2026&month=13');

        $response->assertStatus(404);
    }

    public function test_pr_01()
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

        $response = $this->get('/laporan');

        $response->assertStatus(200);
        $response->assertSee('Semua Tahun');
        $response->assertSee('Semua Bulan');
        $response->assertSee('1');
    }
}
