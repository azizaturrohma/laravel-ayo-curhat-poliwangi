<?php

namespace Tests\Unit\Reporting;

use Tests\TestCase;
use App\Models\Reporting;
use App\Models\ReportingReason;
use App\Models\VictimRequirement;
use App\Models\DisabilityType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_dp_11()
    {
        $response = $this->get('/pengaduan/show/18446744073709551616');

        $response->assertStatus(404);
    }

    public function test_dp_10()
    {
        $response = $this->get('/pengaduan/show/18446744073709551615');

        $response->assertStatus(404);
    }

    public function test_dp_09()
    {
        $response = $this->get('/pengaduan/show/123456789101112131415');

        $response->assertStatus(404);
    }

    public function test_dp_08()
    {
        $response = $this->get('/pengaduan/show/12345678910111213141');

        $response->assertStatus(404);
    }

    public function test_dp_07()
    {
        // Preparation: create reporting
        $user = User::factory()->tamuSatgas()->create();

        $reporting = Reporting::factory()->create([
            'reporter_id' => $user->id,
        ]);

        $reporting->disabilityType()->attach(DisabilityType::factory()->count(2)->create()->pluck('id'));
        $reporting->reportingReason()->attach(ReportingReason::factory()->count(2)->create()->pluck('id'));
        $reporting->victimRequirement()->attach(VictimRequirement::factory()->count(2)->create()->pluck('id'));

        $response = $this->get('/pengaduan/show/1');

        // Expected Result
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_dp_06()
    {
        $response = $this->get('/pengaduan/show/');

        $response->assertStatus(404);
    }

    public function test_dp_05()
    {
        // Preparation: create reporting
        $user = User::factory()->tamuSatgas()->create();

        $reporting = Reporting::factory()->create([
            'reporter_id' => $user->id,
        ]);

        $reporting->disabilityType()->attach(DisabilityType::factory()->count(2)->create()->pluck('id'));
        $reporting->reportingReason()->attach(ReportingReason::factory()->count(2)->create()->pluck('id'));
        $reporting->victimRequirement()->attach(VictimRequirement::factory()->count(2)->create()->pluck('id'));

        $response = $this->get('/pengaduan/show/1');

        // Expected Result
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_dp_04()
    {
        // Tamu Satgas A
        $userA = User::factory()->tamuSatgas()->create();

        // Create pengaduan dari Tamu Satgas A
        $reporting = Reporting::factory()->create([
            'reporter_id' => $userA->id,
        ]);

        $reporting->disabilityType()->attach(DisabilityType::factory()->count(2)->create()->pluck('id'));
        $reporting->reportingReason()->attach(ReportingReason::factory()->count(2)->create()->pluck('id'));
        $reporting->victimRequirement()->attach(VictimRequirement::factory()->count(2)->create()->pluck('id'));

        // Masuk sebagai Tamu Satgas B
        $userB = User::factory()->tamuSatgas()->create();
        $this->actingAs($userB);

        $response = $this->get('/pengaduan/show/' . $reporting->id);

        $response->assertStatus(403);
    }

    public function test_dp_03()
    {
        $response = $this->get('/pengaduan/show/abc!@#');

        $response->assertStatus(404);
    }

    public function test_dp_02()
    {
        $response = $this->get('/pengaduan/show/0');

        $response->assertStatus(404);
    }

    public function test_dp_01()
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

        $response = $this->get(route('reportings.show', $reporting->id));

        // Expected Result
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        // Make sure relasinya diload dengan benar
        $this->assertTrue($reporting->relationLoaded('reportingUser'));
        $this->assertTrue($reporting->relationLoaded('reportingReason'));
        $this->assertTrue($reporting->relationLoaded('reportedStatus'));
        $this->assertTrue($reporting->relationLoaded('disabilityType'));
        $this->assertTrue($reporting->relationLoaded('victimRequirement'));
    }
}
