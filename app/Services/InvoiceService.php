<?php

namespace App\Services;

use App\Models\Contribution\Invoice;
use App\Models\Contribution\InvoiceJournal;
use App\Models\Contribution\MemberFeeSetting;
use App\Models\MasterData\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    protected FeeService $fs;

    public function __construct(FeeService $fs)
    {
        $this->fs = $fs;
    }

    public function createMonthlyInvoice(Member $member, string $period, ?string $due = null, ?string $notes = null): Invoice
    {
        DB::beginTransaction();

        $start = Carbon::parse($period)->startOfMonth();

        $end = $start->copy()->endOfMonth();

        $setting = MemberFeeSetting::where('member_id', $member->id)
            ->where('effective_from', '<=', $end)
            ->where(function ($query) use ($start) {
                $query->whereNull('effective_until')
                    ->orWhereDate(
                        'effective_until',
                        '>=',
                        $start
                    );
            })
            ->orderBy('effective_from')

            ->lockForUpdate()
            ->first();

        $journal = InvoiceJournal::query()->first();

        $code = 'FEE-';
        if (! $journal) {
            throw ValidationException::withMessages([
                'fee' => 'Tarif iuran tidak ditemukan.',
            ]);
        } else {
            $amount = $journal->default_amount;
            $code = $journal->code;
        }

        if ($setting) {
            $amount = $setting->monthly_amount;
        }

        $invoice = Invoice::create([
            'member_id' => $member->id,
            'invoice_number' => self::getNextInvoiceNumber($journal, $start),
            'period_start' => $period,
            'issued_at' => now()->toDateString(),
            'due_at' => $due ?? $start->copy()->day(28)->toDateString(),
        ]);

        $invoice->invoice_items()->create([
            'invoice_journal_id' => $journal->id,
            'description' => $notes ?? 'Iuran Bulan '.$start->translatedFormat('F Y'),
            'quantity' => 1,
            'unit_price' => $amount,
            'amount' => $amount,
        ]);

        DB::commit();

        return $invoice;
    }

    public static function getNextInvoiceNumber(InvoiceJournal $journal, Carbon $start)
    {
        return $journal->code
            .$start->format('Ym')
            .'-'
            .Str::upper(Str::random(8));
    }
}
