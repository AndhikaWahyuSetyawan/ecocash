<?php

namespace Tests\Feature;

use App\Models\Deposit;
use App\Models\EcoPointLedger;
use App\Models\Partner;
use App\Models\User;
use App\Models\WasteCategory;
use App\Models\WastePrice;
use App\Services\EcoPointService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $partnerUser;
    protected Partner $partner;
    protected WasteCategory $category;
    protected WastePrice $price;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user        = User::where('email', 'user@ecocash.test')->firstOrFail();
        $this->partnerUser = User::where('email', 'partner@ecocash.test')->firstOrFail();
        $this->partner     = Partner::first();
        $this->category    = WasteCategory::first();
        $this->price       = WastePrice::where('waste_category_id', $this->category->id)->first();
    }

    public function test_deposit_can_be_created(): void
    {
        $deposit = Deposit::create([
            'user_id'      => $this->user->id,
            'partner_id'   => $this->partner->id,
            'status'       => 'pending_verification',
            'submitted_at' => now(),
        ]);

        $deposit->items()->create([
            'waste_category_id'  => $this->category->id,
            'declared_weight'    => 2.5,
            'price_per_kg'       => $this->price->price_per_kg,
            'estimated_value'    => 10000,
            'estimated_ecopoint' => 10,
        ]);

        $this->assertDatabaseHas('deposits', ['id' => $deposit->id, 'status' => 'pending_verification']);
        $this->assertDatabaseHas('deposit_items', ['deposit_id' => $deposit->id]);
    }

    public function test_ecopoint_not_credited_while_pending(): void
    {
        Deposit::create([
            'user_id'      => $this->user->id,
            'partner_id'   => $this->partner->id,
            'status'       => 'pending_verification',
            'submitted_at' => now(),
        ]);

        $this->assertEquals(0, EcoPointLedger::where('user_id', $this->user->id)->sum('amount'));
    }

    public function test_ecopoint_credited_after_verification(): void
    {
        $deposit = Deposit::create([
            'user_id'      => $this->user->id,
            'partner_id'   => $this->partner->id,
            'status'       => 'pending_verification',
            'submitted_at' => now(),
        ]);

        $deposit->items()->create([
            'waste_category_id'  => $this->category->id,
            'declared_weight'    => 2.5,
            'price_per_kg'       => $this->price->price_per_kg,
            'estimated_value'    => 10000,
            'estimated_ecopoint' => 10,
        ]);

        $service = new EcoPointService();
        $service->credit($deposit, $this->user, 10);

        $this->assertEquals(10, EcoPointLedger::where('user_id', $this->user->id)->sum('amount'));
    }

    public function test_partner_can_verify_deposit(): void
    {
        $deposit = Deposit::create([
            'user_id'      => $this->user->id,
            'partner_id'   => $this->partner->id,
            'status'       => 'pending_verification',
            'submitted_at' => now(),
        ]);

        $deposit->items()->create([
            'waste_category_id'  => $this->category->id,
            'declared_weight'    => 2.5,
            'price_per_kg'       => $this->price->price_per_kg,
            'estimated_value'    => 10000,
            'estimated_ecopoint' => 10,
        ]);

        $this->actingAs($this->partnerUser)
            ->patch(route('partner.deposits.verify', $deposit), [
                'status'          => 'verified',
                'verified_weight' => 2.4,
            ])
            ->assertRedirect(route('partner.dashboard'));

        $this->assertDatabaseHas('deposits', ['id' => $deposit->id, 'status' => 'verified']);
        $this->assertEquals(10, EcoPointLedger::where('user_id', $this->user->id)->sum('amount'));
    }

    public function test_user_cannot_verify_deposit(): void
    {
        $deposit = Deposit::create([
            'user_id'      => $this->user->id,
            'partner_id'   => $this->partner->id,
            'status'       => 'pending_verification',
            'submitted_at' => now(),
        ]);

        $this->actingAs($this->user)
            ->patch(route('partner.deposits.verify', $deposit), ['status' => 'verified'])
            ->assertForbidden();
    }

    public function test_deposit_rejection_stores_reason(): void
    {
        $deposit = Deposit::create([
            'user_id'      => $this->user->id,
            'partner_id'   => $this->partner->id,
            'status'       => 'pending_verification',
            'submitted_at' => now(),
        ]);

        $deposit->items()->create([
            'waste_category_id'  => $this->category->id,
            'declared_weight'    => 2.5,
            'price_per_kg'       => $this->price->price_per_kg,
            'estimated_value'    => 10000,
            'estimated_ecopoint' => 10,
        ]);

        $this->actingAs($this->partnerUser)
            ->patch(route('partner.deposits.verify', $deposit), [
                'status'           => 'rejected',
                'rejection_reason' => 'Material tidak sesuai dengan kategori yang didaftarkan.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('deposits', [
            'id'               => $deposit->id,
            'status'           => 'rejected',
            'rejection_reason' => 'Material tidak sesuai dengan kategori yang didaftarkan.',
        ]);

        // No ecopoint should be credited on rejection.
        $this->assertEquals(0, EcoPointLedger::where('user_id', $this->user->id)->sum('amount'));
    }
}
