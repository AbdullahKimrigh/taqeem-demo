<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>{{ __('Damage Assessment Report') }} — {{ $case->accident_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; direction: rtl; }
        h1 { font-size: 18px; border-bottom: 1px solid #ccc; padding-bottom: 8px; }
        h2 { font-size: 14px; margin-top: 16px; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: right; }
        th { background: #f5f5f5; }
        .text-left { text-align: left; }
        .total { font-weight: bold; margin-top: 12px; }
        .signature { margin-top: 24px; font-size: 11px; color: #666; }
        img { max-width: 120px; max-height: 80px; object-fit: cover; margin: 2px; }
        .images { margin: 12px 0; }
    </style>
</head>
<body>
    <h1>{{ __('Remote Vehicle Damage Estimation — Official Report') }}</h1>
    <p><strong>{{ __('Accident Number:') }}</strong> {{ $case->accident_number }}</p>
    <p><strong>{{ __('Plate Number:') }}</strong> {{ $case->plate_number }}</p>
    <p><strong>{{ __('Report Date:') }}</strong> {{ $case->updated_at->format('Y-m-d H:i') }}</p>

    <h2>{{ __('Vehicle Images') }}</h2>
    <div class="images">
        @foreach($case->images as $img)
            @php
                $imgPath = storage_path('app/public/' . $img->path);
                $dataUri = null;
                if (file_exists($imgPath)) {
                    $mime = mime_content_type($imgPath) ?: (str_ends_with($imgPath, '.jpg') || str_ends_with($imgPath, '.jpeg') ? 'image/jpeg' : 'image/png');
                    $dataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($imgPath));
                }
            @endphp
            @if($dataUri)
                <img src="{{ $dataUri }}" alt="{{ __('Vehicle Images') }}">
            @endif
        @endforeach
    </div>

    <h2>{{ __('Damage Assessment') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('Part') }}</th>
                <th>{{ __('Action') }}</th>
                <th class="text-left">{{ __('Cost') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($case->getDamagesForDisplay() as $d)
                <tr>
                    <td>{{ $d['part'] ?? '—' }}</td>
                    <td>{{ $d['action'] ?? '—' }}</td>
                    <td class="text-left">{{ number_format($d['cost'] ?? 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">{{ __('Total estimated cost:') }} {{ number_format($case->getTotalCost()) }} ({{ __('currency units') }})</p>

    <p><strong>{{ __('Evaluator:') }}</strong> {{ $case->evaluator_name ?? '—' }}</p>
    <p class="signature">{{ __('Electronic signature placeholder — Demo report. Not for official use.') }}</p>
</body>
</html>
