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

class EmergencyCallTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        // Masuk sebagai Tamu Satgas
        $this->actingAs(User::factory()->tamuSatgas()->create());
    }

    // public function test_pd_03()
    // {
    //     $response = $this->get('/emergency-call');

    //     // Expected Result
    //     $response->assertStatus(200);
    //     $response->assertRedirect('https://api.whatsapp.com/send/?phone=6282282560426&text&type=phone_number&app_absent=0');
    // }

    // public function test_pd_02()
    // {
    //     $response = $this->get('/emergency-call');

    //     // Expected Result
    //     $response->assertStatus(200);
    //     $response->assertRedirect('https://api.whatsapp.com/send/?phone=6282282560426&text&type=phone_number&app_absent=0');
    // }

    public function test_pd_01()
    {
        $response = $this->get('/emergency-call');

        // Expected Result
        $response->assertStatus(200);

        $response->assertSee('Panggilan Darurat');
        $response->assertSee('Hubungi Sekarang');
    }
}
