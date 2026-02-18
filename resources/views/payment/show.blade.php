@extends('layouts.app')

@section('title', __('Pay Service Fee'))

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
    <p class="text-slate-500 text-sm mb-2">{{ __('Step') }} 3 {{ __('of') }} 3</p>
    <h1 class="text-xl font-bold text-slate-800 mb-4">{{ __('Service Fee Payment') }}</h1>
    <p class="text-slate-600 mb-4">{{ __('Case') }} <strong>{{ $case->accident_number }}</strong> — {{ __('Plate') }} <strong>{{ $case->plate_number }}</strong></p>
    <p class="text-slate-600 mb-6">{{ __('This is a demo. Click below to simulate successful payment.') }}</p>

    <form action="{{ route('payment.process', $case) }}" method="POST">
        @csrf
        <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded hover:bg-green-700">
            {{ __('Pay Service Fee (Simulate Success)') }}
        </button>
    </form>
    <p class="text-sm text-slate-500 mt-4"><a href="{{ route('home') }}" class="underline">{{ __('Back to Home') }}</a></p>
</div>
@endsection
