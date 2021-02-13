<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function store()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'from' => 'required|date',
            'to' => 'required|date',
            'days' => 'required|array'
        ]);

        $attributes['slug'] = Str::slug($attributes['name']);

        $dates = $this->getDates(Carbon::parse($attributes['from']), Carbon::parse($attributes['to']));
        $dateFromDays = $this->getDateFromDays($attributes['days'], $dates);
        $days = $this->getDays($dateFromDays);

        $event = Event::updateOrCreate(['slug' => $attributes['slug']], Arr::only($attributes, ['name', 'slug']));

        if (!empty($event->days())) {
            $event->days()->delete();
        }

        $event->days()->createMany($days);

        return response()->json($event->load('days'));
    }

    public function getDates(Carbon $from, Carbon $to): array
    {
        $dates = [];

        while ($from <= $to) {
            $dates[] = [
                'day' => strtolower(Carbon::parse($from)->format('D')),
                'date_string' => Carbon::parse($from)->toDateString()
            ];

            $from->addDay();
        }

        return $dates;
    }

    public function getDateFromDays(array $days, array $dates): array
    {
        $dateFromDays = [];

        foreach ($days as $day) {
            $dateFromDays[] = collect($dates)
                ->where('day', $day)
                ->map(function ($date) {
                    return $date['date_string'];
                })
                ->values();
        }

        return collect($dateFromDays)->flatten()->toArray();
    }

    public function getDays(array $dates): array
    {
        $days = [];

        foreach ($dates as $date) {
            $days[] = ['date' => $date];
        }

        return $days;
    }
}
