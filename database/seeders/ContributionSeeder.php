<?php

namespace Database\Seeders;

use App\Models\Contribution\Invoice;
use App\Models\Contribution\InvoiceItem;
use App\Models\Contribution\InvoiceJournal;
use App\Models\Contribution\Payment;
use App\Models\Contribution\PaymentAllocation;
use App\Models\MasterData\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContributionSeeder extends Seeder
{
    /**
     * Seed 100 invoices across existing members with realistic payment statuses:
     *   - ~40 fully paid   (100 % of invoice amount covered by APPROVED payment)
     *   - ~30 partial      (APPROVED payment covers only part of the invoice)
     *   - ~30 not yet paid (no payment at all)
     *
     * Each paid/partial invoice has a payment with a dummy evidence file path.
     */
    public function run(): void
    {
        $journal = InvoiceJournal::first();

        if (! $journal) {
            $this->command->warn('No InvoiceJournal found — run DatabaseSeeder first.');

            return;
        }

        // Re-use existing members (skip the admin member at id=1).
        $members = Member::where('id', '!=', 1)->get();

        if ($members->isEmpty()) {
            $this->command->warn('No members found — run DatabaseSeeder first.');

            return;
        }

        // Spread invoices across 2025 and 2026 (all 12 months).
        $periods = [];
        foreach ([2025, 2026] as $year) {
            for ($month = 1; $month <= 12; $month++) {
                $periods[] = Carbon::create($year, $month, 1);
            }
        }

        DB::beginTransaction();

        $created = 0;
        $targetCount = 100;
        $memberCycle = $members->shuffle();
        $memberIndex = 0;

        // Track used (member_id, period_start) pairs to respect the unique constraint.
        /** @var array<string, bool> */
        $usedPairs = Invoice::query()
            ->select(['member_id', 'period_start'])
            ->get()
            ->mapWithKeys(fn ($r) => [$r->member_id.'_'.$r->period_start => true])
            ->toArray();

        $seq = Invoice::max('id') ?? 0;

        while ($created < $targetCount) {
            /** @var Member $member */
            $member = $memberCycle[$memberIndex % $memberCycle->count()];
            $memberIndex++;

            // Pick a random period this member doesn't already have.
            $period = null;
            foreach (collect($periods)->shuffle() as $p) {
                $key = $member->id.'_'.$p->toDateString();
                if (! isset($usedPairs[$key])) {
                    $period = $p;
                    $usedPairs[$key] = true;
                    break;
                }
            }

            // All periods for this member are taken; move to the next.
            if (! $period) {
                continue;
            }

            $seq++;
            $invoiceNumber = 'INV-'.$period->format('Ym').'-'.str_pad($seq, 5, '0', STR_PAD_LEFT);
            $feeAmount = $journal->default_amount; // e.g. 50 000

            // Create invoice.
            $invoice = Invoice::create([
                'member_id' => $member->id,
                'invoice_number' => $invoiceNumber,
                'period_start' => $period->toDateString(),
                'issued_at' => $period->copy()->addDays(3)->toDateString(),
                'due_at' => $period->copy()->addMonth()->toDateString(),
                'status' => 'issued',
                'notes' => null,
            ]);

            // Create one invoice item for the standard fee.
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'invoice_journal_id' => $journal->id,
                'description' => 'Iuran '.$period->translatedFormat('F Y'),
                'quantity' => 1,
                'unit_price' => $feeAmount,
                'amount' => $feeAmount,
            ]);

            // Determine payment scenario by bucket.
            $bucket = $this->bucket($created, $targetCount);
            $this->applyPayment($invoice, $member, $feeAmount, $bucket, $period);

            $created++;
        }

        DB::commit();

        $this->command->info("Created {$created} invoices with varied payment statuses.");
    }

    /**
     * Assign a scenario bucket based on position in the 100-invoice sequence.
     *   0 – 39  → 'full'
     *  40 – 69  → 'partial'
     *  70 – 99  → 'none'
     */
    private function bucket(int $index, int $total): string
    {
        $pct = $index / $total;

        if ($pct < 0.40) {
            return 'full';
        }

        if ($pct < 0.70) {
            return 'partial';
        }

        return 'none';
    }

    /**
     * Create payment + allocation for the invoice according to the bucket.
     *
     * @param  numeric-string|float  $feeAmount
     */
    private function applyPayment(
        Invoice $invoice,
        Member $member,
        float $feeAmount,
        string $bucket,
        Carbon $period
    ): void {
        if ($bucket === 'none') {
            return;
        }

        $paymentAmount = match ($bucket) {
            'full' => $feeAmount,
            'partial' => round($feeAmount * fake()->randomFloat(2, 0.2, 0.8)),
            default => $feeAmount,
        };

        // Use one of the existing dummy evidence files (or a realistic path).
        $evidencePath = $this->fakeEvidencePath();

        // Determine payment status — mix in some PENDING/REVIEWED for partial.
        $status = match (true) {
            $bucket === 'full' => 'APPROVED',
            $bucket === 'partial' && fake()->boolean(30) => fake()->randomElement(['PENDING', 'REVIEWED']),
            default => 'APPROVED',
        };

        $paidAt = $period->copy()->addDays(fake()->numberBetween(1, 28));

        $payment = Payment::create([
            'member_id' => $member->id,
            'payment_number' => 'PAY-'.$paidAt->format('Ymd').'-'.Str::upper(Str::random(8)),
            'paid_at' => $paidAt,
            'amount' => $paymentAmount,
            'method' => fake()->randomElement(['transfer', 'cash', 'qris']),
            'reference_number' => 'REF-'.fake()->numerify('########'),
            'status' => $status,
            'notes' => fake()->optional(0.25)->sentence(),
            'evidence_path' => $evidencePath,
        ]);

        PaymentAllocation::create([
            'payment_id' => $payment->id,
            'invoice_id' => $invoice->id,
            'amount' => $paymentAmount,
        ]);
    }

    /**
     * Return a realistic dummy evidence path (reuses existing storage filenames
     * when available, otherwise generates a plausible one).
     */
    private function fakeEvidencePath(): string
    {
        static $existingFiles = null;

        if ($existingFiles === null) {
            $dir = storage_path('app/public/payments/evidence');
            $existingFiles = is_dir($dir)
                ? array_values(array_filter(scandir($dir), fn ($f) => ! in_array($f, ['.', '..'])))
                : [];
        }

        if (! empty($existingFiles)) {
            return 'payments/evidence/'.fake()->randomElement($existingFiles);
        }

        return 'payments/evidence/'.Str::random(40).'.jpg';
    }
}
