<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Vehicle Damage Estimation') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .badge-submitted { background: #e0e7ff; color: #3730a3; }
        .badge-ready { background: #d1fae5; color: #065f46; }
        .badge-needs { background: #fef3c7; color: #92400e; }
        .badge-escalated { background: #fce7f3; color: #9d174d; }
        .badge-physical { background: #fed7aa; color: #9a3412; }
        .badge-completed { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen antialiased">
    <header class="bg-slate-800 text-white shadow">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-semibold text-lg">Remote Vehicle Damage Estimation</a>
            <nav class="flex gap-4 text-sm">
                <a href="{{ route('home') }}" class="hover:underline">Home</a>
                <a href="{{ route('customer.index') }}" class="hover:underline">Customer</a>
                <a href="{{ route('evaluator.index') }}" class="hover:underline">Evaluator</a>
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="max-w-5xl mx-auto px-4 py-4 text-slate-500 text-sm border-t mt-8">
        Demo MVP — Accident Assessment Center. Not for production use.
    </footer>
</body>
</html>
