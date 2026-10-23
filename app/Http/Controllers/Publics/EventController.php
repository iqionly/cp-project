<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display the active event and upcoming events.
     */
    public function index(Request $request): View
    {
        $allEvents = $this->getActiveAndUpcomingEvents();
        $currentEvent = $allEvents->first();

        return $this->renderEventView($currentEvent, $allEvents);
    }

    /**
     * Display a specific active or upcoming public event.
     */
    public function show(Request $request, Event $event): View
    {
        $event->loadMissing(['post', 'post.user']);

        if (! $event->post || empty($event->post->published_at)) {
            abort(404);
        }

        $rawEndAt = $event->getRawOriginal('end_at');
        $rawStartAt = $event->getRawOriginal('start_at');
        if ($rawEndAt < now() && $rawStartAt < now()) {
            abort(404);
        }

        $allEvents = $this->getActiveAndUpcomingEvents();

        return $this->renderEventView($event, $allEvents);
    }

    /**
     * Retrieve all published, active, and upcoming events ordered by start date.
     * Older/past events are excluded.
     *
     * @return Collection<int, Event>
     */
    private function getActiveAndUpcomingEvents(): Collection
    {
        return Event::query()
            ->with(['post', 'post.user'])
            ->whereHas('post', function (Builder $query) {
                $query->whereNotNull('published_at');
            })
            ->where(function (Builder $query) {
                $query->where('end_at', '>=', now())
                    ->orWhere('start_at', '>=', now());
            })
            ->orderBy('start_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Render the public event blade view with navigation items.
     *
     * @param  Collection<int, Event>  $allEvents
     */
    private function renderEventView(?Event $currentEvent, Collection $allEvents): View
    {
        $nextEvent = null;
        $prevEvent = null;

        if ($currentEvent !== null && $allEvents->isNotEmpty()) {
            $currentIndex = $allEvents->search(function (Event $item) use ($currentEvent) {
                return $item->id === $currentEvent->id;
            });

            if ($currentIndex !== false) {
                if ($currentIndex < $allEvents->count() - 1) {
                    $nextEvent = $allEvents->get($currentIndex + 1);
                }

                if ($currentIndex > 0) {
                    $prevEvent = $allEvents->get($currentIndex - 1);
                }
            }
        }

        return view('pages.publics.event', [
            'currentEvent' => $currentEvent,
            'nextEvent' => $nextEvent,
            'prevEvent' => $prevEvent,
            'allEvents' => $allEvents,
            'data' => $allEvents,
            'page_title' => $currentEvent?->post?->title ?? 'Events',
            'page_breadcumb' => [
                'Events',
                $currentEvent?->post?->title ? Str::limit($currentEvent->post->title, 35) : 'All',
            ],
        ]);
    }
}
