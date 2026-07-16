<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenueController extends Controller
{
    /**
     * Display the venue details and availability.
     */
    public function show(Venue $venue)
    {
        // Fetch upcoming confirmed bookings to show the user
        $upcomingBookings = $venue->bookings()
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('venues.show', compact('venue', 'upcomingBookings'));
    }

    /**
     * Check if the venue is available at the requested date and time.
     */
    public function checkAvailability(Request $request, Venue $venue)
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $date = $request->date;
        $start = $request->start_time;
        $end = $request->end_time;

        // Overlap logic: A_start < B_end AND A_end > B_start
        $isBooked = Booking::where('venue_id', $venue->id)
            ->where('date', $date)
            ->where('status', 'approved')
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($isBooked) {
            return back()->withInput()->with('status_type', 'error')->with('status_message', 'This venue is already booked for the selected date and time.');
        }

        return back()->withInput()->with('status_type', 'success')->with('status_message', 'Good news! The venue is available at the selected date and time.');
    }

    /**
     * Book the venue.
     */
    public function book(Request $request, Venue $venue)
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $date = $request->date;
        $start = $request->start_time;
        $end = $request->end_time;

        // Double check availability before saving
        $isBooked = Booking::where('venue_id', $venue->id)
            ->where('date', $date)
            ->where('status', 'approved')
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($isBooked) {
            return back()->withInput()->with('status_type', 'error')->with('status_message', 'Oops, someone just booked that slot! Please choose another time.');
        }

        Booking::create([
            'venue_id' => $venue->id,
            'user_id' => Auth::id(),
            'date' => $date,
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'approved',
        ]);

        return back()->with('status_type', 'success_booking')->with('status_message', 'Congratulations! Your venue booking has been confirmed.');
    }
    /**
     * Admin: Toggle block for a specific date on a venue.
     */
    public function toggleBlock(Request $request, Venue $venue)
    {
        if (Auth::user()?->role !== \App\Enums\UserRole::Admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $date = $request->date;

        // Check if there is already a block for this entire day (00:00 to 23:59)
        $existingBlock = Booking::where('venue_id', $venue->id)
            ->where('date', $date)
            ->where('start_time', '00:00:00')
            ->where('end_time', '23:59:59')
            ->first();

        if ($existingBlock) {
            $existingBlock->delete();
            return response()->json(['message' => 'Unblocked successfully', 'status' => 'unblocked']);
        } else {
            Booking::create([
                'venue_id' => $venue->id,
                'user_id' => Auth::id(),
                'date' => $date,
                'start_time' => '00:00:00',
                'end_time' => '23:59:59',
                'status' => 'approved',
            ]);
            return response()->json(['message' => 'Blocked successfully', 'status' => 'blocked']);
        }
    }
}
