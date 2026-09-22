<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WastePrice;
use App\Services\EcoPointService;
use App\Services\WasteValueCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcocashTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_login_screen_renders(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_user_can_login(): void
    {
        $user = User::where('email', 'user@ecocash.test')->firstOrFail();
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/dashboard');
    }

    public function test_unauthenticated_cannot_access_scanner(): void
    {
        $this->get('/scanner')->assertRedirect('/login');
    }

    public function test_bank_partner_can_access_partner_dashboard(): void
    {
        $partner = User::where('email', 'partner@ecocash.test')->firstOrFail();
        $this->actingAs($partner)->get('/partner/dashboard')->assertOk();
    }

    public function test_user_cannot_access_partner_dashboard(): void
    {
        $user = User::where('email', 'user@ecocash.test')->firstOrFail();
        $this->actingAs($user)->get('/partner/dashboard')->assertForbidden();
    }

    public function test_waste_value_calculation(): void
    {
        $price = WastePrice::first();
        $this->assertNotNull($price, 'No price seeded');
        $service = new WasteValueCalculatorService();
        $result  = $service->calculate(2.5, $price);
        $this->assertEquals((int) round(2.5 * $price->price_per_kg), $result);
    }

    public function test_ecopoint_calculation(): void
    {
        $service = new EcoPointService();
        // Rp 10,000 should yield 10 points (default 1000 Rp per point)
        $this->assertEquals(10, $service->pointsForValue(10000));
    }

    public function test_scanner_route_accessible_when_authenticated(): void
    {
        $user = User::where('email', 'user@ecocash.test')->firstOrFail();
        $this->actingAs($user)->get('/scanner')->assertOk();
    }

    public function test_dashboard_route_accessible_when_authenticated(): void
    {
        $user = User::where('email', 'user@ecocash.test')->firstOrFail();
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }
}
