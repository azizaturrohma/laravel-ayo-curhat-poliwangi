<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Reporting;
use App\Models\ReportingReason;
use App\Models\VictimRequirement;
use App\Models\DisabilityType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Smalot\PdfParser\Parser;

class PeriodReportDownloadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        // Masuk sebagai Admin Satgas
        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_ur_05()
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

        $response = $this->get('/laporan/unduh?year2025&month=1');

        // Expected Result
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseContent($response->getContent());
        $text = $pdf->getText();

        // Cek filter di PDF
        $this->assertStringContainsString('January 2025', $text);
    }
}
