<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Vehicle Damage Estimation')) — {{ config('app.name') }}</title>
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
            <a href="{{ route('home') }}" class="font-semibold text-lg">{{ __('Remote Vehicle Damage Estimation') }}</a>
            <nav class="flex gap-4 text-sm">
                <a href="{{ route('home') }}" class="hover:underline">{{ __('Home') }}</a>
                <a href="{{ route('customer.index') }}" class="hover:underline">{{ __('Customer') }}</a>
                <a href="{{ route('evaluator.index') }}" class="hover:underline">{{ __('Evaluator') }}</a>
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-800 rounded">
                {{ __(session('success')) }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-800 rounded">
                {{ __(session('error')) }}
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="max-w-5xl mx-auto px-4 py-4 text-slate-500 text-sm border-t mt-8">
        {{ __('Demo MVP — Accident Assessment Center. Not for production use.') }}
    </footer>

    @if(session('action_popup'))
    <div id="action-popup" role="dialog" aria-modal="true" aria-labelledby="action-popup-title"
         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem; background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 border border-slate-200" style="max-height: 90vh; overflow: auto;">
            <h2 id="action-popup-title" class="text-lg font-semibold text-slate-800 mb-3">{{ __('Notification') }}</h2>
            <p class="text-slate-600 mb-6">{{ __(session('action_popup')) }}</p>
            <button type="button" onclick="document.getElementById('action-popup').remove()" class="w-full px-4 py-2 bg-slate-800 text-white rounded hover:bg-slate-700">
                {{ __('OK') }}
            </button>
        </div>
    </div>
    @endif
</body>
</html>
