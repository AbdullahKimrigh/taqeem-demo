<?php

namespace App\Http\Controllers;

use App\Models\DamageCase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function show(DamageCase $case): View
    {
        $case->load('images');
        return view('report.show', compact('case'));
    }

    public function download(DamageCase $case): Response
    {
        $case->load('images');
        $html = view('report.pdf', compact('case'))->render();
        $pdf = Pdf::loadHTML($html)->setPaper('a4');
        return $pdf->download("damage-report-{$case->accident_number}.pdf");
    }
}
