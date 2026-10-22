<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Models\Contribution\FeeType;
use App\Models\Contribution\Invoice;
use App\Models\Contribution\InvoiceJournal;
use App\Models\MasterData\Member;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generate(Request $request, InvoiceService $invoiceService)
    {
        $members = Member::select('id', 'user_id')->get();
        if($request->isMethod('post')) {
            foreach($members as $member) {
                $invoiceService->createMonthlyInvoice($member, $request->period_start, $request->due_at, $request->notes);
            }

            return back()->with('success', 'Success create invoices.');
        }

        $journal = InvoiceJournal::get();
        return view('pages.members.invoice_generate', [
            'journals' => $journal,
            'members' => $members,
        ]);
    }
}
