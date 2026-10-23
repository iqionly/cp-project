<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Models\Contribution\Invoice;
use App\Models\Contribution\InvoiceItem;
use App\Models\Contribution\Payment;
use App\Models\Contribution\PaymentAllocation;
use App\Models\MasterData\Member;
use App\Scopes\SearchMemberFilter;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    /***
     * Iuran Page Member
     */
    public function index(Request $request)
    {
        $memberId = $request->user()->member->id;

        return view('pages.members.contrib_index', [
            'data' => Invoice::with([
                'member',
                'invoice_items',
            ])
                ->when($request->context()->role_grouping != 'admin', function (Builder $query) use ($memberId) {
                    $query->where('member_id', $memberId);
                })
                ->tap(new SearchMemberFilter($request))
                ->orderBy('created_at', 'desc')
                ->paginate(
                    $request->input('limit'),
                    ['*'],
                    'page',
                    (int) $request->input('page', 1),
                ),
            'payments' => Payment::query()
                ->when($request->context()->role_grouping != 'admin', function (Builder $query) use ($memberId) {
                    $query->where('member_id', $memberId);
                })
                ->tap(new SearchMemberFilter($request))
                ->orderBy('created_at', 'desc')
                ->paginate($request->input('limit', 10)),
            'page_title' => $request->context()->role_grouping != 'admin' ? 'Your Contribution' : 'Invoices & Payments Members',
        ]);
    }

    public function create(Request $request, PaymentService $paymentService, ?Member $member = null)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'invoice_id' => 'required|exists:invoices,id',
                'amount' => 'required|numeric|min:0.01',
                'method' => 'required|string|max:20',
                'evidence' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $evidencePath = $request->file('evidence')->storePublicly('payments/evidence', 'public');

            $payment = $paymentService->recordPayment(
                $request->user()->member->id,
                $request->invoice_id,
                $request->amount,
                $request->method,
                $evidencePath
            );

            return redirect()->route('contrib.index')->with('success', 'Payment submitted successfully and is awaiting review.');
        }

        $invoices = Invoice::where('member_id', $request->user()->member->id)
            ->where('status', 'issued')
            ->get();

        return view('pages.members.contrib_edit', [
            'invoices' => $invoices,
            'data' => null,
            'payments' => PaymentAllocation::where('invoice_id', -1)
                ->with('payment')
                ->paginate($request->input('limit', 10)),
        ]);
    }

    public function edit(Request $request, Invoice $mp)
    {
        return view('pages.members.contrib_edit', [
            'invoices' => [$mp],
            'selectedInvoice' => $mp,
            'data' => $mp,
            'items' => $mp->invoice_items,
            'payments' => PaymentAllocation::where('invoice_id', $mp->id)
                ->with('payment')
                ->paginate($request->input('limit', 10)),
        ]);
    }

    public function delete(Request $request, Invoice $invoice)
    {
        if ($request->context()->role_grouping !== 'admin') {
            abort(403, 'Only admins can delete invoices.');
        }

        if ($invoice->paid_amount > 0) {
            return back()->with('error', 'Cannot delete an invoice that has already received payments.');
        }

        $invoice->allocations()->delete();
        $invoice->delete();

        return redirect()->route('contrib.index')->with('success', 'Invoice deleted successfully.');
    }

    public function storeItem(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_journal_id' => 'required|exists:invoice_journals,id',
            'description' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $invoice->invoice_items()->create($request->all());

        return back()->with('success', 'Invoice item added successfully.');
    }

    public function updateItem(Request $request, InvoiceItem $item)
    {
        if (
            $request->context()->role_grouping !== 'admin' &&
            $item->invoice->member_id !== $request->user()->member->id
        ) {
            abort(403, 'You can only edit items on your own invoices.');
        }

        $request->validate([
            'description' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $item->update($request->all());

        return back()->with('success', 'Invoice item updated successfully.');
    }

    public function destroyItem(InvoiceItem $item)
    {
        if (
            request()->context()->role_grouping !== 'admin' &&
            $item->invoice->member_id !== request()->user()->member->id
        ) {
            abort(403, 'You can only delete items on your own invoices.');
        }

        $item->delete();

        return back()->with('success', 'Invoice item removed successfully.');
    }

    public function updatePayment(Request $request, Payment $payment)
    {
        if (
            $request->context()->role_grouping !== 'admin' &&
            $payment->member_id !== $request->user()->member->id
        ) {
            abort(403, 'You can only edit your own payments.');
        }

        if ($payment->status === 'APPROVED') {
            return back()->with('error', 'Approved payments cannot be edited.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|max:20',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'evidence_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('evidence_path')) {
            $data['evidence_path'] = $request->file('evidence_path')->storePublicly('payments/evidence', 'public');
        }

        $payment->update($data);

        return back()->with('success', 'Payment updated successfully.');
    }

    public function destroyPayment(Payment $payment)
    {
        if (
            request()->context()->role_grouping !== 'admin' &&
            $payment->member_id !== request()->user()->member->id
        ) {
            abort(403, 'You can only delete your own payments.');
        }

        if ($payment->status === 'APPROVED') {
            return back()->with('error', 'Approved payments cannot be deleted.');
        }

        PaymentAllocation::where('payment_id', $payment->id)->delete();
        $payment->delete();

        return back()->with('success', 'Payment deleted successfully.');
    }

    public function adminReview(Request $request, Payment $payment, bool $status = false)
    {
        if ($payment->status == 'REVIEWED') {
            $payment->approve($status);
        } else {
            $payment->review($status);
        }
        $payment->save();

        return back()->with('success', 'Payment status updated to '.($status ? 'REVIEWED' : 'REJECTED'));
    }
}
