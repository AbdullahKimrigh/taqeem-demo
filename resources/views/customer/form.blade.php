@extends('layouts.app')

@section('title', 'Submit Accident')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Submit Accident Report</h1>

    <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
        @csrf

        <div class="mb-4">
            <label for="accident_number" class="block text-sm font-medium text-slate-700 mb-1">Accident Number *</label>
            <input type="text" name="accident_number" id="accident_number" value="{{ old('accident_number') }}"
                   class="w-full rounded border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-slate-500 focus:border-slate-500"
                   required>
            @error('accident_number')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="plate_number" class="block text-sm font-medium text-slate-700 mb-1">Plate Number *</label>
            <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number') }}"
                   class="w-full rounded border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-slate-500 focus:border-slate-500"
                   required>
            @error('plate_number')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-1">Vehicle Images (min. 6: 4 sides + damage area) *</label>
            <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg"
                   class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-slate-100 file:text-slate-700">
            @error('images')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded hover:bg-slate-700">
                Submit Case
            </button>
            <a href="{{ route('home') }}" class="px-4 py-2 border border-slate-300 rounded text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>
</div>
@endsection
