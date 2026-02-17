<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Damage Report — {{ $case->accident_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; border-bottom: 1px solid #ccc; padding-bottom: 8px; }
        h2 { font-size: 14px; margin-top: 16px; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f5f5f5; }
        .text-right { text-align: right; }
        .total { font-weight: bold; margin-top: 12px; }
        .signature { margin-top: 24px; font-size: 11px; color: #666; }
        img { max-width: 120px; max-height: 80px; object-fit: cover; margin: 2px; }
        .images { margin: 12px 0; }
    </style>
</head>
<body>
    <h1>Remote Vehicle Damage Estimation — Official Report</h1>
    <p><strong>Accident Number:</strong> {{ $case->accident_number }}</p>
    <p><strong>Plate Number:</strong> {{ $case->plate_number }}</p>
    <p><strong>Report Date:</strong> {{ $case->updated_at->format('Y-m-d H:i') }}</p>

    <h2>Vehicle Images</h2>
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
                <img src="{{ $dataUri }}" alt="Vehicle image">
            @endif
        @endforeach
    </div>

    <h2>Damage Assessment</h2>
    <table>
        <thead>
            <tr>
                <th>Part</th>
                <th>Action</th>
                <th class="text-right">Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($case->getDamagesForDisplay() as $d)
                <tr>
                    <td>{{ $d['part'] ?? '—' }}</td>
                    <td>{{ $d['action'] ?? '—' }}</td>
                    <td class="text-right">{{ number_format($d['cost'] ?? 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total estimated cost: {{ number_format($case->getTotalCost()) }} (currency units)</p>

    <p><strong>Evaluator:</strong> {{ $case->evaluator_name ?? '—' }}</p>
    <p class="signature">Electronic signature placeholder — Demo report. Not for official use.</p>
</body>
</html>
