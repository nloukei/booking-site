<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $venue->name }} - Availability - {{ config('app.name', 'Venue Booking') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col justify-between">

    {{-- Navbar --}}
    <header class="w-full border-b border-gray-100 bg-white">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="text-xl font-semibold tracking-tight">
                {{ config('app.name', 'VenueBook') }}
            </a>

            <nav class="flex items-center gap-4 text-sm">
                <a href="/" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">
                    ← Back to home
                </a>
                @auth
                    <span class="text-gray-400">|</span>
                    <span class="text-gray-600">Hi, <strong>{{ auth()->user()->name }}</strong></span>
                    @if(auth()->user()->role === \App\Enums\UserRole::Admin)
                        <a href="/admin" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors font-medium cursor-pointer">
                            Log out
                        </button>
                    </form>
                @else
                    <span class="text-gray-400">|</span>
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors font-medium">
                        Log in
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Main details section --}}
    <main class="flex-1 max-w-5xl w-full mx-auto px-6 py-12">
        
        {{-- Cover Image --}}
        <div class="w-full h-80 md:h-[400px] overflow-hidden rounded-2xl bg-gray-100 mb-10 shadow-xs">
            @php
                $imageSrc = $venue->image_path ?: 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1280&q=80';
                if ($venue->image_path && !str_starts_with($venue->image_path, 'http')) {
                    $imageSrc = asset('storage/' . $venue->image_path);
                }
            @endphp
            <img src="{{ $imageSrc }}" alt="{{ $venue->name }}" class="w-full h-full object-cover">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            {{-- Left column: Details --}}
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-900">{{ $venue->name }}</h1>
                    
                    <div class="flex items-center gap-6 text-sm text-gray-500 mt-3">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $venue->location }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Accommodates up to {{ $venue->capacity }} guests
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h2 class="text-xl font-semibold mb-3">About the Space</h2>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $venue->description }}</p>
                </div>

                {{-- Upcoming Bookings --}}
                <div class="border-t border-gray-100 pt-6">
                    <h2 class="text-xl font-semibold mb-4">Reserved Times</h2>
                    @if($upcomingBookings->isEmpty())
                        <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-500 text-center">
                            No reservations scheduled. This venue is completely open!
                        </div>
                    @else
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-xs">
                            <div class="divide-y divide-gray-100">
                                @foreach($upcomingBookings as $booking)
                                    <div class="p-4 flex items-center justify-between text-sm">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="font-medium text-gray-900">{{ $booking->date->format('l, M j, Y') }}</span>
                                        </div>
                                        <div class="text-gray-500">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                            {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right column: Booking Widget --}}
            <div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs sticky top-8">
                    <div class="flex items-baseline justify-between mb-6 pb-6 border-b border-gray-100">
                        <span class="text-sm text-gray-500 font-medium">Hourly Rate</span>
                        <div class="text-right">
                            <span class="text-2xl font-bold">${{ number_format($venue->price_per_hour, 2) }}</span>
                            <span class="text-xs text-gray-400 block">per hour</span>
                        </div>
                    </div>

                    {{-- Feedback Messages --}}
                    @if (session('status_message'))
                        @if (session('status_type') === 'success')
                            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm rounded-lg">
                                {{ session('status_message') }}
                            </div>
                        @elseif (session('status_type') === 'success_booking')
                            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm rounded-lg flex flex-col gap-2">
                                <span class="font-semibold">{{ session('status_message') }}</span>
                                <a href="/" class="text-emerald-700 underline text-xs font-semibold hover:text-emerald-950">
                                    Browse other venues
                                </a>
                            </div>
                        @else
                            <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-800 text-sm rounded-lg">
                                {{ session('status_message') }}
                            </div>
                        @endif
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-800 text-xs rounded-lg">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Calendar Widget --}}
                    <div class="mb-6">
                        <x-venue-mini-calendar :venue="$venue" :isAdmin="false" />
                    </div>

                    {{-- Form --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-gray-900 tracking-tight">Check Availability</h3>
                        
                        <form method="POST" action="{{ route('venues.check', $venue) }}" id="availability-form" class="space-y-4">
                            @csrf
                            <div>
                                <label for="date" class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                                <input type="date" name="date" id="date" value="{{ old('date', request('date')) }}" required min="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-hidden focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_time" class="block text-xs font-medium text-gray-500 mb-1">Start Time</label>
                                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time', request('start_time')) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-hidden focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900">
                                </div>
                                <div>
                                    <label for="end_time" class="block text-xs font-medium text-gray-500 mb-1">End Time</label>
                                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time', request('end_time')) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-hidden focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900">
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium cursor-pointer">
                                Check Availability
                            </button>
                        </form>

                        {{-- Booking Button --}}
                        <div class="pt-4 border-t border-gray-100">
                            @auth
                                <form method="POST" action="{{ route('venues.book', $venue) }}">
                                    @csrf
                                    {{-- Pass the values from the check form --}}
                                    <input type="hidden" name="date" value="{{ old('date') }}">
                                    <input type="hidden" name="start_time" value="{{ old('start_time') }}">
                                    <input type="hidden" name="end_time" value="{{ old('end_time') }}">

                                    @if(old('date') && old('start_time') && old('end_time') && session('status_type') === 'success')
                                        <button type="submit"
                                            class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-colors text-sm font-medium shadow-sm cursor-pointer">
                                            Confirm and Book Now
                                        </button>
                                    @else
                                        <button type="submit" disabled
                                            class="w-full px-4 py-2.5 bg-gray-100 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                                            Book this Space
                                        </button>
                                        <p class="text-[10px] text-gray-400 text-center mt-2">
                                            Please check availability for your date and time before booking.
                                        </p>
                                    @endif
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                    class="w-full text-center block px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">
                                    Sign in to Book Space
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full border-t border-gray-100 py-6 text-center text-sm text-gray-400 bg-white">
        &copy; {{ date('Y') }} {{ config('app.name', 'VenueBook') }}. All rights reserved.
    </footer>

</body>

</html>
