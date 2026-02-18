@extends('layouts.app')

@section('title', __('Assessment') . ' — ' . $case->accident_number)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ __('Case') }}: {{ $case->accident_number }}</h1>
        @php
            $statusBadge = match($case->status) {
                'Submitted' => 'badge-submitted',
                'Ready for Assessment' => 'badge-ready',
                'Needs More Images' => 'badge-needs',
                'Escalated' => 'badge-escalated',
                'Require Physical Visit' => 'badge-physical',
                'Completed' => 'badge-completed',
                default => 'bg-slate-200 text-slate-700',
            };
        @endphp
        <p class="text-slate-600">{{ __('Plate') }}: {{ $case->plate_number }} — {{ __('Status') }}: <span class="px-2 py-0.5 rounded text-sm {{ $statusBadge }}">{{ __($case->status) }}</span></p>
    </div>
    <a href="{{ route('evaluator.index') }}" class="text-slate-600 underline">{{ __('Back to list') }}</a>
</div>

<div class="grid lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
        <h2 class="font-semibold text-slate-800 mb-3">{{ __('Vehicle Images') }}</h2>
        <div class="grid grid-cols-2 gap-2">
            @foreach($case->images as $img)
                <a href="{{ asset('storage/' . $img->path) }}" target="_blank" class="block rounded border border-slate-200 overflow-hidden">
                    <img src="{{ asset('storage/' . $img->path) }}" alt="{{ __('Vehicle Images') }}" class="w-full h-32 object-cover">
                </a>
            @endforeach
        </div>
    </div>

    <div class="space-y-4">
        @php $result = $case->ai_result ?? []; $damages = $case->final_result['damages'] ?? $result['damages'] ?? []; @endphp

        @if(!empty($result))
            <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                <h2 class="font-semibold text-slate-800 mb-3">{{ __('AI Pre-Analysis') }}</h2>
                <p class="text-sm text-slate-600 mb-2">{{ __('Estimated cost range:') }} {{ number_format($result['cost_range']['min'] ?? 0) }} – {{ number_format($result['cost_range']['max'] ?? 0) }} ({{ __('currency units') }})</p>
                <table class="w-full text-sm">
                    <thead><tr class="border-b"><th class="text-right py-1">{{ __('Part') }}</th><th class="text-right py-1">{{ __('Action') }}</th><th class="text-left py-1">{{ __('Cost') }}</th></tr></thead>
                    <tbody>
                        @foreach($result['damages'] ?? [] as $d)
                            <tr class="border-b border-slate-100"><td>{{ $d['part'] }}</td><td>{{ $d['action'] }}</td><td class="text-left">{{ number_format($d['cost']) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
            <h2 class="font-semibold text-slate-800 mb-3">{{ __('Edit Damages & Notes') }}</h2>
            <form action="{{ route('evaluator.update', $case) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <label class="block text-sm font-medium text-slate-700">{{ __('Notes') }}</label>
                <textarea name="notes" rows="2" class="w-full rounded border border-slate-300 px-3 py-2">{{ $case->notes }}</textarea>
                <label class="block text-sm font-medium text-slate-700">{{ __('Damages (JSON array: part, action, cost)') }}</label>
                <textarea name="damages" rows="8" class="w-full rounded border border-slate-300 px-3 py-2 font-mono text-sm">{{ json_encode($damages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded hover:bg-slate-600">{{ __('Save changes') }}</button>
            </form>
        </div>

        @if($case->status !== \App\Models\DamageCase::STATUS_COMPLETED)
            <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                <h2 class="font-semibold text-slate-800 mb-3">{{ __('Action') }}</h2>
                <form action="{{ route('evaluator.action', $case) }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="text" name="evaluator_name" placeholder="{{ __('Your name (for report)') }}" value="{{ old('evaluator_name') }}" class="w-full rounded border border-slate-300 px-3 py-2 mb-2">
                    <div class="flex flex-wrap gap-2">
                        <button type="submit" name="action" value="approve" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">{{ __('Approve') }}</button>
                        <button type="submit" name="action" value="more_images" class="px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600">{{ __('Request More Images') }}</button>
                        <button type="submit" name="action" value="escalate" class="px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700">{{ __('Escalate to Senior') }}</button>
                        <button type="submit" name="action" value="physical" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">{{ __('Require Physical Visit') }}</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
