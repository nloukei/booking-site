<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create an account - {{ config('app.name', 'Venue Booking') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col justify-between">

    <header class="w-full border-b border-gray-100 bg-white">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="text-xl font-semibold tracking-tight">
                {{ config('app.name', 'VenueBook') }}
            </a>
            <a href="/login" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                Already have an account? Sign in
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md bg-white border border-gray-200/80 rounded-xl p-8 shadow-sm">
            <div class="mb-8">
                <h1 class="text-2xl font-semibold tracking-tight">Create your account</h1>
                <p class="text-sm text-gray-500 mt-1">Get started with a free account today.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-lg text-sm text-red-600">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-3.5 py-2 border border-gray-300 rounded-lg shadow-xs focus:outline-hidden focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 text-sm transition-all"
                        placeholder="John Doe">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-3.5 py-2 border border-gray-300 rounded-lg shadow-xs focus:outline-hidden focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 text-sm transition-all"
                        placeholder="you@example.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3.5 py-2 border border-gray-300 rounded-lg shadow-xs focus:outline-hidden focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 text-sm transition-all"
                        placeholder="••••••••">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-3.5 py-2 border border-gray-300 rounded-lg shadow-xs focus:outline-hidden focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 text-sm transition-all"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium text-sm shadow-sm cursor-pointer">
                    Register
                </button>
            </form>
        </div>
    </main>

    <footer class="w-full border-t border-gray-100 py-6 text-center text-sm text-gray-400 bg-white">
        &copy; {{ date('Y') }} {{ config('app.name', 'VenueBook') }}. All rights reserved.
    </footer>

</body>

</html>
