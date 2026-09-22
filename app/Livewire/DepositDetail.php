<?php

namespace App\Livewire;

use App\Models\Deposit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DepositDetail extends Component
{
    public Deposit $deposit;

    public function mount(Deposit $deposit): void
    {
        // Pastikan otorisasi: hanya pemilik atau mitra/admin yang dapat melihat
        abort_unless(
            $deposit->user_id === Auth::id()
                || Auth::user()->isRole('admin')
                || Auth::user()->isRole('bank_partner'),
            403
        );

        $this->deposit = $deposit->load(['partner', 'items.category', 'verifier']);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.deposit-detail')->layout('layouts.app');
    }
}
