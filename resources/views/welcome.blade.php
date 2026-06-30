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

    {{-- Hero Section --}}
    <main class="flex-1 flex items-center justify-center px-6">
        <div class="max-w-2xl text-center">
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
                    <div class="text-sm text-gray-500">
                        You are logged in as a user! Soon you will be able to book venues here.
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="inline-block px-6 py-3 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors text-sm font-medium">
                    Get Started
                </a>
            @endauth
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