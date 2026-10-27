<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    /**
     * Display the agenda and event schedule for members.
     */
    public function index(Request $request): View
    {
        $member = $request->user()->member;

        $events = Event::query()
            ->with(['post', 'registrations.member'])
            ->withCount('registrations')
            ->whereHas('post', function ($query) {
                $query->whereNotNull('published_at');
            })
            ->where('start_at', '>=', date('Y-m-d H:i:s'))
            ->orderBy('start_at', 'asc')
            ->paginate(10);

        $myRegisteredEventIds = $member
            ? EventRegistration::where('member_id', $member->id)->pluck('event_id')->toArray()
            : [];

        return view('pages.members.agenda', [
            'events' => $events,
            'myRegisteredEventIds' => $myRegisteredEventIds,
            'member' => $member,
            'page_title' => 'Agenda & Kalender Kegiatan',
            'page_breadcumb' => ['Member', 'Agenda Kegiatan'],
        ]);
    }

    /**
     * Register the authenticated member for an event.
     */
    public function register(Request $request, Event $event): RedirectResponse
    {
        $member = $request->user()->member;

        if (! $member) {
            return back()->with('error', 'Profil member belum tersedia. Lengkapi profil terlebih dahulu.');
        }

        // Cek apakah sudah terdaftar
        $existing = EventRegistration::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Anda sudah terdaftar pada kegiatan ini.');
        }

        // Cek kuota peserta jika max_attendant diset > 0
        if ($event->max_attendant > 0) {
            $currentRegistrantsCount = $event->registrations()->count();
            if ($currentRegistrantsCount >= $event->max_attendant) {
                return back()->with('error', 'Mohon maaf, kuota peserta untuk kegiatan ini sudah penuh.');
            }
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'registered_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'Berhasil mendaftar pada kegiatan "'.$event->post?->title.'".');
    }

    /**
     * Cancel the member's registration for an event.
     */
    public function unregister(Request $request, Event $event): RedirectResponse
    {
        $member = $request->user()->member;

        if (! $member) {
            return back()->with('error', 'Profil member tidak ditemukan.');
        }

        $registration = EventRegistration::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($registration) {
            $registration->delete();

            return back()->with('success', 'Pendaftaran kegiatan berhasil dibatalkan.');
        }

        return back()->with('info', 'Anda belum terdaftar pada kegiatan ini.');
    }
}
