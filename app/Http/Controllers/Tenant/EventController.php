<?php

namespace App\Http\Controllers\Tenant;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function index()
    {
        $today = Event::where('event_date', date('Y-m-d'))->first();

        $events = Event::get()
            ->map(function($event) {
                return [
                    'title' => $event->title,
                    'start' => $event->event_date,
                    'end' => $event->event_date,
                    'color' => $event->status == 0 ? '#dc3545' : '#28a745'
                ];
            });

        return view('admin.events.index', compact('events', 'today'));
    }

    public function store(Request $request)
    {
        if (!empty($request->event_id)) {
            Event::find($request->event_id)->update($request->all());
        }
        else {
            Event::create($request->all());
        }

        return redirect('/events')->with('success', 'Data saved.');
    }

    public function checkEvent($date)
    {
        $event = Event::where('event_date', $date)->first();

        return response()->json(['event' => $event]);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect('/events')->with('success', 'Data deleted.');
    }

    public function calendar()
    {
        $today = Event::where('event_date', date('Y-m-d'))->first();

        $events = Event::get()
            ->map(function($event) {
                return [
                    'title' => $event->title,
                    'start' => $event->event_date,
                    'end' => $event->event_date,
                    'color' => $event->status == 0 ? '#dc3545' : '#28a745'
                ];
            });

        return view('admin.events.calendar', compact('events', 'today'));
    }
}
