<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Authorizations\Role;
use App\Models\Event;
use App\Models\MasterData\Member;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /**
     * Generate an unused, sequential member code like ID00001.
     */
    protected function generateMemberCode(): string
    {
        $lastMember = Member::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = ($lastMember ? $lastMember->id : 0) + 1;
        $code = 'ID'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);

        while (Member::withTrashed()->where('member_code', $code)->exists()) {
            $nextId++;
            $code = 'ID'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    public function index(Request $request)
    {
        $upcomingEvents = Event::query()
            ->activeOrUpcoming()
            ->with('post')
            ->get();

        return view('pages.masterdata.member_index', [
            'data' => Member::with(['user', 'invoices.invoice_items', 'invoices.allocations.payment'])->paginate(
                $request->input('limit'),
                ['*'],
                'page',
                (int) $request->input('page', 1),
            ),
            'upcomingEvents' => $upcomingEvents,
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users,name'],
                'password' => ['required', 'string', 'min:6'],
                'member_code' => ['nullable', 'string', 'max:7', 'unique:members,member_code'],
            ]);

            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->input('username'),
                    'password' => Hash::make($request->input('password')),
                    'email' => null,
                ]);

                $memberRole = Role::where('name', 'member')->first() ?? Role::where('role_grouping', 'member')->first();
                if ($memberRole) {
                    $user->roles()->syncWithoutDetaching([$memberRole->id]);
                }

                $memberCode = $request->input('member_code') ?: $this->generateMemberCode();

                $member = new Member;
                $member->member_code = $memberCode;
                $member->user()->associate($user);
                $member->save();
            });

            return redirect()->route('master-data.member.list')
                ->with('success', 'Member account created successfully for username: '.$request->input('username').' with default password: '.$request->input('password'));
        }

        return view('pages.masterdata.member_create', [
            'defaultMemberCode' => $this->generateMemberCode(),
            'defaultPassword' => 'password',
        ]);
    }

    public function edit(Request $request, Member $member)
    {
        if ($request->isMethod('put')) {
            $data = $request->except([
                '_token',
                '_method',
                'member_code',
                'email',
            ]);

            if ($request->filled('email') && $member->user) {
                $request->validate([
                    'email' => ['email', 'unique:users,email,'.$member->user->id],
                ]);
                $member->user->email = $request->input('email');
                $member->user->save();
            }

            $member->fill($data);
            $member->save();

            return redirect()->back()->with('success', 'Member details updated successfully.');
        }

        return view('pages.masterdata.member_edit', [
            'data' => $member,
        ]);
    }

    public function delete(Request $request, Member $member)
    {
        if ($member->delete()) {
            return redirect()->back();
        }
    }

    /**
     * Notify member regarding active iuran via WhatsApp.
     */
    public function notifyIuran(Request $request, Member $member, WhatsAppNotificationService $waService)
    {
        if (! $member->hasValidWhatsAppNumber()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Member ini belum memiliki nomor WhatsApp yang valid.',
                ], 422);
            }

            return back()->with('error', 'Member ini belum memiliki nomor WhatsApp yang valid.');
        }

        $activeInvoices = $member->getActiveInvoices();
        if ($activeInvoices->isEmpty()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Member ini tidak memiliki tagihan iuran yang aktif.',
                ], 422);
            }

            return back()->with('info', 'Member ini tidak memiliki tagihan iuran yang aktif.');
        }

        $selectedInvoice = null;
        if ($request->filled('invoice_id')) {
            $selectedInvoice = $activeInvoices->firstWhere('id', (int) $request->input('invoice_id'));
        }

        $message = $waService->generateIuranMessage($member, $selectedInvoice);
        $waUrl = $waService->buildWhatsAppUrl($member->formattedWhatsAppNumber(), $message);

        // Record reminder tracking in invoice_reminders
        if ($selectedInvoice) {
            $waService->recordInvoiceReminder($selectedInvoice);
        } else {
            foreach ($activeInvoices as $inv) {
                $waService->recordInvoiceReminder($inv);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'wa_url' => $waUrl,
                'phone' => $member->formattedWhatsAppNumber(),
                'message' => $message,
                'active_invoices_count' => $activeInvoices->count(),
                'total_amount' => $member->getTotalActiveIuranAmount(),
            ]);
        }

        return redirect()->away($waUrl);
    }

    /**
     * Notify member regarding an active/upcoming event via WhatsApp.
     */
    public function notifyEvent(Request $request, Member $member, WhatsAppNotificationService $waService, ?Event $event = null)
    {
        if (! $member->hasValidWhatsAppNumber()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Member ini belum memiliki nomor WhatsApp yang valid.',
                ], 422);
            }

            return back()->with('error', 'Member ini belum memiliki nomor WhatsApp yang valid.');
        }

        if (! $event || ! $event->exists) {
            if ($request->filled('event_id')) {
                $event = Event::with('post')->find($request->input('event_id'));
            } else {
                $event = Event::query()->activeOrUpcoming()->with('post')->first();
            }
        }

        if (! $event) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada kegiatan/event aktif atau mendatang untuk diinformasikan.',
                ], 404);
            }

            return back()->with('error', 'Tidak ada kegiatan/event aktif atau mendatang untuk diinformasikan.');
        }

        $message = $waService->generateEventMessage($member, $event);
        $waUrl = $waService->buildWhatsAppUrl($member->formattedWhatsAppNumber(), $message);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'wa_url' => $waUrl,
                'phone' => $member->formattedWhatsAppNumber(),
                'message' => $message,
                'event_title' => $event->post?->title,
            ]);
        }

        return redirect()->away($waUrl);
    }
}
