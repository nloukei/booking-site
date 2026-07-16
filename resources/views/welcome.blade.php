<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Venue Booking') }} - Find & Book Venues</title>
    <meta name="description" content="Find and book the perfect venue for your next event. Simple, fast, and reliable venue booking.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-900 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <header class="w-full border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="text-xl font-semibold tracking-tight">
                {{ config('app.name', 'VenueBook') }}
            </a>

            <nav class="flex items-center gap-4 text-sm">
                @auth
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
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors font-medium">
                        Log in
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 bg-gray-50/40 py-12 md:py-16">
        <div class="max-w-5xl mx-auto px-6">

            {{-- Hero Section --}}
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h1 class="text-4xl md:text-5xl font-semibold tracking-tight leading-tight mb-4">
                    Book the perfect venue for your event
                </h1>
                <p class="text-lg text-gray-500 mb-8 max-w-lg mx-auto">
                    Browse available venues, check real-time availability, and reserve your space in just a few clicks.
                </p>
                @auth
                    @if(auth()->user()->role === \App\Enums\UserRole::Admin)
                        <a href="/admin"
                           class="inline-block px-6 py-3 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors text-sm font-medium">
                            Go to Admin Dashboard
                        </a>
                    @else

                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="inline-block px-6 py-3 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors text-sm font-medium">
                        Get Started
                    </a>
                @endauth
            </div>

            {{-- Venues Section --}}
            <div class="border-t border-gray-100 pt-12">
                <div class="flex flex-col md:flex-row md:items-baseline justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-semibold tracking-tight">Explore Venues</h2>
                        <p class="text-sm text-gray-500 mt-1">Discover premium spaces suited for any occasion</p>
                    </div>
                    <span class="text-sm text-gray-400 mt-2 md:mt-0 font-medium">
                        Showing {{ $venues->count() }} option{{ $venues->count() !== 1 ? 's' : '' }}
                    </span>
                </div>

                @if($venues->isEmpty())
                    <div class="text-center py-16 bg-white border border-gray-200/60 rounded-xl">
                        <p class="text-gray-400">No venues available yet. Check back soon!</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($venues as $venue)
                            @php
                                $imageSrc = $venue->image_path ?: 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80';
                                if ($venue->image_path && !str_starts_with($venue->image_path, 'http')) {
                                    $imageSrc = asset('storage/' . $venue->image_path);
                                }
                            @endphp
                            <div class="group bg-white rounded-xl border border-gray-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 flex flex-col">
                                {{-- Venue Image --}}
                                <div class="relative h-48 overflow-hidden bg-gray-100">
                                    <img src="{{ $imageSrc }}" alt="{{ $venue->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute top-4 right-4 bg-gray-900/90 backdrop-blur-xs text-white text-xs font-semibold px-2.5 py-1.5 rounded-md">
                                        ${{ number_format($venue->price_per_hour, 0) }} / hr
                                    </div>
                                </div>

                                {{-- Venue Details --}}
                                <div class="p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold tracking-tight text-gray-900 group-hover:text-gray-700 transition-colors">
                                            {{ $venue->name }}
                                        </h3>
                                        
                                        <div class="flex items-center gap-4 text-xs text-gray-500 mt-2 mb-4">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $venue->location }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                Up to {{ $venue->capacity }} guests
                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed mb-6">
                                            {{ $venue->description }}
                                        </p>
                                    </div>

                                    <a href="{{ route('venues.show', $venue) }}" 
                                       class="w-full text-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium text-xs rounded-lg transition-colors border border-gray-200/60 block mt-auto">
                                        Check Availability
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-6 py-6 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'VenueBook') }}. All rights reserved.
        </div>
    </footer>

</body>

</html>