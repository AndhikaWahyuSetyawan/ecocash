<?php

namespace App\Livewire;

use App\Models\InAppNotification;
use App\Models\WithdrawalMethod;
use App\Models\WithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WithdrawalFlow extends Component
{
    public ?int $methodId = null;
    public string $accountName = '';
    public string $accountNumber = '';
    public ?int $amount = null;

    public bool $isSubmitting = false;
    public ?string $errorMessage = null;

    protected function rules(): array
    {
        return [
            'methodId'      => 'required|exists:withdrawal_methods,id',
            'accountName'   => 'required|string|max:100',
            'accountNumber' => 'required|string|max:50',
            'amount'        => 'required|integer|min:10000',
        ];
    }

    protected function messages(): array
    {
        return [
            'methodId.required'      => 'Silakan pilih metode penarikan.',
            'accountName.required'   => 'Nama pemilik rekening/e-wallet wajib diisi.',
            'accountNumber.required' => 'Nomor rekening atau nomor HP e-wallet wajib diisi.',
            'amount.required'        => 'Masukkan nominal penarikan.',
            'amount.min'             => 'Minimal penarikan adalah Rp10.000.',
        ];
    }

    public function submit(WalletService $walletService): void
    {
        $this->errorMessage = null;
        $this->validate();

        $user = Auth::user();
        $account = $walletService->getOrCreateAccount($user);

        if ($account->balance < $this->amount) {
            $this->errorMessage = 'Saldo kakak saat ini (Rp' . number_format($account->balance, 0, ',', '.') . ') belum mencukupi untuk penarikan ini.';
            return;
        }

        $this->isSubmitting = true;

        try {
            $method = WithdrawalMethod::findOrFail($this->methodId);
            $fee = (int) $method->fee;
            $netAmount = max(0, $this->amount - $fee);

            $withdrawal = WithdrawalRequest::create([
                'user_id'              => $user->id,
                'withdrawal_method_id' => $method->id,
                'account_name'         => $this->accountName,
                'account_number'       => $this->accountNumber,
                'amount'               => $this->amount,
                'fee'                  => $fee,
                'net_amount'           => $netAmount,
                'status'               => 'pending',
            ]);

            // Potong saldo secara atomik via WalletService
            $walletService->debitForWithdrawal(
                $user,
                $this->amount,
                $withdrawal,
                'Permohonan penarikan ke ' . $method->name . ' (' . $this->accountNumber . ')'
            );

            // Beri notifikasi
            InAppNotification::create([
                'user_id' => $user->id,
                'title' => 'Permohonan Penarikan Diajukan',
                'message' => 'Permohonan penarikan sebesar Rp' . number_format($this->amount, 0, ',', '.') . ' berhasil diajukan dan sedang menunggu proses transfer dari admin.',
                'type' => 'withdrawal_submitted',
                'action_url' => '/wallet',
            ]);

            session()->flash('status', 'Permohonan penarikan berhasil dikirim! Admin akan memproses pencairan dalam 1x24 jam.');
            $this->redirect(route('wallet'), navigate: false);
        } catch (\Throwable $e) {
            $this->errorMessage = 'Terjadi kendala saat memproses penarikan: ' . $e->getMessage();
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        $methods = WithdrawalMethod::where('is_active', true)->get();

        return view('livewire.withdrawal-flow', [
            'user'    => $user,
            'methods' => $methods,
            'balance' => $user->available_balance,
        ])->layout('layouts.app');
    }
}
