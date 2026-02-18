@extends('layouts.app')

@section('title', __('Home'))

@section('content')
<div class="text-center max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-2">{{ __('Remote Vehicle Damage Estimation Platform') }}</h1>
    <p class="text-slate-600 mb-8">{{ __('Select how you would like to continue.') }}</p>

    <div class="grid gap-6 sm:grid-cols-2">
        <a href="{{ route('customer.index') }}" class="block p-6 bg-white border-2 border-slate-200 rounded-lg shadow-sm hover:border-slate-400 hover:shadow transition">
            <span class="text-4xl mb-2 block">🚗</span>
            <h2 class="font-semibold text-lg text-slate-800">{{ __('Continue as Customer') }}</h2>
            <p class="text-sm text-slate-600 mt-1">{{ __('Submit your accident details and vehicle images for remote assessment.') }}</p>
        </a>
        <a href="{{ route('evaluator.index') }}" class="block p-6 bg-white border-2 border-slate-200 rounded-lg shadow-sm hover:border-slate-400 hover:shadow transition">
            <span class="text-4xl mb-2 block">📋</span>
            <h2 class="font-semibold text-lg text-slate-800">{{ __('Evaluator Dashboard') }}</h2>
            <p class="text-sm text-slate-600 mt-1">{{ __('Review cases, view AI suggestions, and complete assessments.') }}</p>
        </a>
    </div>
</div>
@endsection
