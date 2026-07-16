<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class BookingCalendarWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.booking-calendar-widget';

    protected int | string | array $columnSpan = 'full';

    public function getBookingsProperty()
    {
        return \App\Models\Booking::with(['venue', 'user'])
            ->whereIn('status', ['pending', 'approved'])
            ->get()
            ->groupBy(function($booking) {
                return \Carbon\Carbon::parse($booking->date)->format('Y-m-d');
            });
    }
}
