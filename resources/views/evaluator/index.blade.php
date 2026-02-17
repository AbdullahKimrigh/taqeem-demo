@extends('layouts.app')

@section('title', 'Evaluator Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Evaluator Dashboard</h1>

<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('evaluator.index') }}" class="px-3 py-1 rounded {{ !request('status') ? 'bg-slate-800 text-white' : 'bg-slate-200 text-slate-700' }}">All</a>
    @foreach(['Submitted', 'Ready for Assessment', 'Needs More Images', 'Escalated', 'Require Physical Visit', 'Completed'] as $s)
        <a href="{{ route('evaluator.index', ['status' => $s]) }}" class="px-3 py-1 rounded {{ request('status') === $s ? 'bg-slate-800 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $s }}</a>
    @endforeach
</div>

<div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
    <table class="w-full text-left">
        <thead class="bg-slate-100">
            <tr>
                <th class="px-4 py-3 font-medium text-slate-700">Accident #</th>
                <th class="px-4 py-3 font-medium text-slate-700">Plate</th>
                <th class="px-4 py-3 font-medium text-slate-700">Status</th>
                <th class="px-4 py-3 font-medium text-slate-700">Created</th>
                <th class="px-4 py-3 font-medium text-slate-700">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cases as $c)
                <tr class="border-t border-slate-200 hover:bg-slate-50">
                    <td class="px-4 py-3">{{ $c->accident_number }}</td>
                    <td class="px-4 py-3">{{ $c->plate_number }}</td>
                    <td class="px-4 py-3">
                        @php
                            $badge = match($c->status) {
                                'Submitted' => 'badge-submitted',
                                'Ready for Assessment' => 'badge-ready',
                                'Needs More Images' => 'badge-needs',
                                'Escalated' => 'badge-escalated',
                                'Require Physical Visit' => 'badge-physical',
                                'Completed' => 'badge-completed',
                                default => 'bg-slate-200 text-slate-700',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded text-sm {{ $badge }}">{{ $c->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $c->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('evaluator.show', $c) }}" class="text-slate-700 underline hover:no-underline">Open</a>
                        @if($c->status === 'Completed')
                            <span class="text-slate-400">|</span>
                            <a href="{{ route('report.show', $c) }}" class="text-slate-700 underline hover:no-underline">Report</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">No cases found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
