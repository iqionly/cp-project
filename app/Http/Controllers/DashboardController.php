<?php

namespace App\Http\Controllers;

use App\Models\Contribution\Invoice;
use App\Models\MasterData\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        $user = $request->user();

        if ($user && $request->attributes->get('context') == null) {
            $context = $user->roles->first();

            return redirect()->route('home', ['context' => $context->role_grouping]);
        }

        if ($user && $user->roles()->where('role_grouping', 'member')->exists() && ! $user->isProfileComplete()) {
            return redirect()->route('member.profile')
                ->with('warning', 'Please complete your details info (email, address, and member information) to continue.');
        }

        $isAdmin = $request->context()?->role_grouping === 'admin';

        if (! $isAdmin) {
            return view('pages.homepage');
        }

        $selectedYear = (int) $request->input('year', now()->year);

        // ── Eager-load invoices for the selected year with items and approved allocations ──
        $invoicesThisYear = Invoice::with([
            'invoice_items',
            'allocations' => fn ($q) => $q->whereHas(
                'payment',
                fn ($q2) => $q2->where('status', 'APPROVED')
            ),
        ])
            ->whereYear('period_start', $selectedYear)
            ->get();

        // ── Monthly breakdown ──────────────────────────────────────────────────
        /** @var array<int, float> */
        $monthlyPaid = array_fill(1, 12, 0.0);
        /** @var array<int, float> */
        $monthlyOutstanding = array_fill(1, 12, 0.0);
        /** @var array<int, int> */
        $monthlyFullyPaid = array_fill(1, 12, 0);
        /** @var array<int, int> */
        $monthlyPartial = array_fill(1, 12, 0);
        /** @var array<int, int> */
        $monthlyNotYet = array_fill(1, 12, 0);

        foreach ($invoicesThisYear as $invoice) {
            $month = new Carbon($invoice->period_start)->month;
            $totalAmount = (float) $invoice->invoice_items->sum('amount');
            $paidAmount = (float) $invoice->allocations->sum('amount');
            $remaining = max(0.0, $totalAmount - $paidAmount);

            $monthlyPaid[$month] += $paidAmount;
            $monthlyOutstanding[$month] += $remaining;

            if ($totalAmount > 0 && $remaining <= 0) {
                $monthlyFullyPaid[$month]++;
            } elseif ($paidAmount > 0) {
                $monthlyPartial[$month]++;
            } else {
                $monthlyNotYet[$month]++;
            }
        }

        // ── Year-level totals (for stat cards) ───────────────────────────────
        $totalBilledThisYear = array_sum($monthlyPaid) + array_sum($monthlyOutstanding);
        $totalPaidThisYear = array_sum($monthlyPaid);
        $totalOutstandingThisYear = array_sum($monthlyOutstanding);

        $fullyPaidCount = array_sum($monthlyFullyPaid);
        $partialCount = array_sum($monthlyPartial);
        $notYetCount = array_sum($monthlyNotYet);

        // ── Yearly overview: billed vs paid across all years ──────────────────
        $allInvoices = Invoice::with([
            'invoice_items',
            'allocations' => fn ($q) => $q->whereHas(
                'payment',
                fn ($q2) => $q2->where('status', 'APPROVED')
            ),
        ])->get();

        /** @var array<int, float> */
        $yearlyBilled = [];
        /** @var array<int, float> */
        $yearlyPaid = [];

        foreach ($allInvoices as $invoice) {
            $yr = new Carbon($invoice->period_start)->year;
            $total = (float) $invoice->invoice_items->sum('amount');
            $paid = (float) $invoice->allocations->sum('amount');
            $yearlyBilled[$yr] = ($yearlyBilled[$yr] ?? 0.0) + $total;
            $yearlyPaid[$yr] = ($yearlyPaid[$yr] ?? 0.0) + $paid;
        }

        ksort($yearlyBilled);
        ksort($yearlyPaid);

        $availableYears = array_reverse(array_keys($yearlyBilled));
        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        $totalMembers = Member::query()->count();

        return view('pages.homepage', [
            'page_title' => 'Dashboard',
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
            'totalMembers' => $totalMembers,
            'totalBilledThisYear' => $totalBilledThisYear,
            'totalPaidThisYear' => $totalPaidThisYear,
            'totalOutstandingThisYear' => $totalOutstandingThisYear,
            'fullyPaidCount' => $fullyPaidCount,
            'partialCount' => $partialCount,
            'notYetCount' => $notYetCount,
            'monthlyPaid' => array_values($monthlyPaid),
            'monthlyOutstanding' => array_values($monthlyOutstanding),
            'monthlyFullyPaid' => array_values($monthlyFullyPaid),
            'monthlyPartial' => array_values($monthlyPartial),
            'monthlyNotYet' => array_values($monthlyNotYet),
            'yearlyBilled' => $yearlyBilled,
            'yearlyPaid' => $yearlyPaid,
        ]);
    }
}
