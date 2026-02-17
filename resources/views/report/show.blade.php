@extends('layouts.app')

@section('title', 'Report — ' . $case->accident_number)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-slate-800">Damage Assessment Report</h1>
    <a href="{{ route('report.download', $case) }}" class="px-4 py-2 bg-slate-800 text-white rounded hover:bg-slate-700">Download PDF</a>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm report-content">
    <div class="border-b border-slate-200 pb-4 mb-4">
        <h2 class="text-lg font-semibold text-slate-800">Remote Vehicle Damage Estimation — Official Report</h2>
        <p class="text-slate-600 text-sm">Accident # {{ $case->accident_number }} | Plate: {{ $case->plate_number }}</p>
    </div>

    <div class="mb-6">
        <h3 class="font-semibold text-slate-800 mb-2">Vehicle Images</h3>
        <div class="grid grid-cols-3 gap-2">
            @foreach($case->images as $img)
                <img src="{{ asset('storage/' . $img->path) }}" alt="Vehicle" class="w-full h-24 object-cover rounded border">
            @endforeach
        </div>
    </div>

    <table class="w-full border border-slate-200 mb-6">
        <thead class="bg-slate-100">
            <tr>
                <th class="border border-slate-200 px-3 py-2 text-left">Part</th>
                <th class="border border-slate-200 px-3 py-2 text-left">Action</th>
                <th class="border border-slate-200 px-3 py-2 text-right">Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($case->getDamagesForDisplay() as $d)
                <tr>
                    <td class="border border-slate-200 px-3 py-2">{{ $d['part'] ?? '-' }}</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $d['action'] ?? '-' }}</td>
                    <td class="border border-slate-200 px-3 py-2 text-right">{{ number_format($d['cost'] ?? 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="font-semibold text-slate-800">Total estimated cost: {{ number_format($case->getTotalCost()) }} (currency units)</p>
    <p class="text-slate-600 text-sm mt-4">Evaluator: {{ $case->evaluator_name ?? '—' }}</p>
    <p class="text-slate-500 text-sm mt-2">Electronic signature placeholder — This is a demo report.</p>
</div>
@endsection
