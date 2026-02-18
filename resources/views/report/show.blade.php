@extends('layouts.app')

@section('title', __('Report') . ' — ' . $case->accident_number)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-slate-800">{{ __('Damage Assessment Report') }}</h1>
    <a href="{{ route('report.download', $case) }}" class="px-4 py-2 bg-slate-800 text-white rounded hover:bg-slate-700">{{ __('Download PDF') }}</a>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm report-content">
    <div class="border-b border-slate-200 pb-4 mb-4">
        <h2 class="text-lg font-semibold text-slate-800">{{ __('Remote Vehicle Damage Estimation — Official Report') }}</h2>
        <p class="text-slate-600 text-sm">{{ __('Accident #') }} {{ $case->accident_number }} | {{ __('Plate') }}: {{ $case->plate_number }}</p>
    </div>

    <div class="mb-6">
        <h3 class="font-semibold text-slate-800 mb-2">{{ __('Vehicle Images') }}</h3>
        <div class="grid grid-cols-3 gap-2">
            @foreach($case->images as $img)
                <img src="{{ asset('storage/' . $img->path) }}" alt="{{ __('Vehicle Images') }}" class="w-full h-24 object-cover rounded border">
            @endforeach
        </div>
    </div>

    <table class="w-full border border-slate-200 mb-6">
        <thead class="bg-slate-100">
            <tr>
                <th class="border border-slate-200 px-3 py-2 text-right">{{ __('Part') }}</th>
                <th class="border border-slate-200 px-3 py-2 text-right">{{ __('Action') }}</th>
                <th class="border border-slate-200 px-3 py-2 text-left">{{ __('Cost') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($case->getDamagesForDisplay() as $d)
                <tr>
                    <td class="border border-slate-200 px-3 py-2">{{ $d['part'] ?? '—' }}</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $d['action'] ?? '—' }}</td>
                    <td class="border border-slate-200 px-3 py-2 text-left">{{ number_format($d['cost'] ?? 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="font-semibold text-slate-800">{{ __('Total estimated cost:') }} {{ number_format($case->getTotalCost()) }} ({{ __('currency units') }})</p>
    <p class="text-slate-600 text-sm mt-4">{{ __('Evaluator:') }} {{ $case->evaluator_name ?? '—' }}</p>
    <p class="text-slate-500 text-sm mt-2">{{ __('Electronic signature placeholder — This is a demo report.') }}</p>
</div>
@endsection
