@extends('layouts.app')

@section('title', __('Select Appointment'))

@section('content')
<div class="max-w-2xl mx-auto">
    <p class="text-slate-500 text-sm mb-2">{{ __('Step') }} 1 {{ __('of') }} 3</p>
    <h1 class="text-2xl font-bold text-slate-800 mb-6">{{ __('Select Appointment') }}</h1>
    <p class="text-slate-600 mb-6">{{ __('Choose your preferred date and time slot for the assessment.') }}</p>

    <form action="{{ route('customer.store-appointment') }}" method="POST" class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
        @csrf

        <div class="mb-4">
            <label for="appointment_date" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Date') }}</label>
            <input type="date" name="appointment_date" id="appointment_date" min="{{ date('Y-m-d') }}" value="{{ old('appointment_date') }}"
                   class="w-full rounded border border-slate-300 px-3 py-2" required>
            @error('appointment_date')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="appointment_slot" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Time slot') }}</label>
            <select name="appointment_slot" id="appointment_slot" class="w-full rounded border border-slate-300 px-3 py-2" required>
                <option value="09:00 - 12:00" {{ old('appointment_slot') === '09:00 - 12:00' ? 'selected' : '' }}>{{ __('Morning') }} (09:00 - 12:00)</option>
                <option value="14:00 - 17:00" {{ old('appointment_slot') === '14:00 - 17:00' ? 'selected' : '' }}>{{ __('Afternoon') }} (14:00 - 17:00)</option>
            </select>
            @error('appointment_slot')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded hover:bg-slate-700">
                {{ __('Next: Accident details') }}
            </button>
            <a href="{{ route('home') }}" class="px-4 py-2 border border-slate-300 rounded text-slate-700 hover:bg-slate-50">{{ __('Cancel') }}</a>
        </div>
    </form>
</div>
@endsection
