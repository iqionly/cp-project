<?php

namespace App\Services;

use App\Models\Contribution\FeeType;
use App\Models\Contribution\Invoice;
use App\Models\Contribution\MemberFeeSetting;
use App\Models\Contribution\Payment;
use App\Models\MasterData\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    protected FeeService $fs;

    public function __construct(FeeService $fs)
    {
        $this->fs = $fs;
    }

    public function recordPayment(
        int $memberId,
        int $invoiceId,
        string $amount,
        string $method = 'transfer',
        ?string $evidencePath = null,
    ): Payment {
        DB::beginTransaction();
        // Gunakan format desimal, bukan float.
        if (
            !preg_match('/^\d+(\.\d{1,2})?$/', $amount)
            || $amount <= 0
        ) {
            throw ValidationException::withMessages([
                'amount' => 'Nominal pembayaran tidak valid.',
            ]);
        }

        $invoice = Invoice::query()
            ->whereKey($invoiceId)
            ->lockForUpdate()
            ->firstOrFail();

        if ((int) $invoice->member_id !== $memberId && !Auth::user()->can('role', ['admin'])) {
            throw ValidationException::withMessages([
                'invoice' => 'Tagihan tidak valid.',
            ]);
        }

        $total = (string) $invoice->invoice_items()
            ->sum('amount');

        $paid = (string) $invoice->allocations()
            ->whereHas('payment', function ($query) {
                $query->where('status', 'confirmed');
            })
            ->sum('amount');

        $remaining = $total - $paid;

        if ($amount > $remaining) {
            throw ValidationException::withMessages([
                'amount' => 'Pembayaran melebihi sisa tagihan.',
            ]);
        }

        $payment = Payment::create([
            'member_id' => $memberId,
            'payment_number' => 'PAY-'
                . now()->format('Ymd')
                . '-'
                . Str::upper(Str::random(8)),
            'paid_at' => now(),
            'amount' => $amount,
            'method' => $method,
            'evidence_path' => $evidencePath,
        ]);

        $payment->allocations()->create([
            'invoice_id' => $invoice->id,
            'amount' => $amount,
        ]);

        if(empty($invoice->issued_at)) {
            $invoice->issued_at = date('Y-m-d');
            $invoice->save();
        }

        DB::commit();
        return $payment;
    }
}
