<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $ttl = config('cache.ttl'); // seconds

        $events = cache()->remember('events', $ttl, function () {
            $events = Event::query()
                ->where('is_external', false)
                ->take(5)
                ->latest('start_date')
                ->get();

            return $events;
        });

        // $coaches = User::has('roles', function($q) {
        //     $q->contains('slug', 'coach');
        // })->inRandomOrder->limit(3)->get();

        $coaches = cache()->remember('coaches', $ttl, function () {
            $coaches = User::query()
                ->has('userStaff')
                ->with('profile', 'userStaff', 'userStaff.type')
                ->where('id', '<>', 1)
                // ->orderBy('name')
                ->get();

            return $coaches;
        });

        return view('front.home-one', compact('events', 'coaches'));
        // return view('front.home-two');
    }

    public function about()
    {
        return view('front.about');
    }

    public function contact()
    {
        return view('front.contact');
    }
}
