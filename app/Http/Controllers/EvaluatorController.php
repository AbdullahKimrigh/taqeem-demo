<?php

namespace App\Http\Controllers;

use App\Models\DamageCase;
use App\Services\DamageAnalyzerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluatorController extends Controller
{
    public function __construct(
        private DamageAnalyzerService $analyzer
    ) {}

    public function index(Request $request): View
    {
        $query = DamageCase::with('images')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cases = $query->get();

        return view('evaluator.index', compact('cases'));
    }

    public function show(DamageCase $case): View|RedirectResponse
    {
        $case->load('images');

        if (! $case->ai_result && $case->status === DamageCase::STATUS_READY) {
            $result = $this->analyzer->analyze($case);
            $case->update(['ai_result' => $result]);
        }

        return view('evaluator.show', compact('case'));
    }

    public function update(Request $request, DamageCase $case): RedirectResponse
    {
        $case->load('images');

        if ($request->filled('notes')) {
            $case->update(['notes' => $request->notes]);
        }

        if ($request->filled('damages')) {
            $damages = is_array($request->damages) ? $request->damages : json_decode($request->damages, true);
            $total = 0;
            foreach ($damages as $d) {
                $total += (float) ($d['cost'] ?? 0);
            }
            $current = $case->final_result ?? $case->ai_result ?? [];
            $case->update([
                'final_result' => array_merge($current, [
                    'damages' => $damages,
                    'estimated_total' => $total,
                ]),
            ]);
        }

        return redirect()->back()->with('success', 'Assessment updated.');
    }

    public function action(Request $request, DamageCase $case): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,more_images,escalate,physical',
            'evaluator_name' => 'nullable|string|max:100',
        ]);

        $evaluatorName = $request->evaluator_name ?: 'Evaluator';

        switch ($request->action) {
            case 'approve':
                $case->update([
                    'status' => DamageCase::STATUS_COMPLETED,
                    'evaluator_name' => $evaluatorName,
                    'final_result' => $case->final_result ?? $case->ai_result,
                ]);
                return redirect()->route('report.show', $case)
                    ->with('success', 'Case approved. Report generated.');

            case 'more_images':
                $case->update(['status' => DamageCase::STATUS_NEEDS_IMAGES]);
                return redirect()->route('evaluator.index')->with('success', 'Case set to Needs More Images.');

            case 'escalate':
                $case->update(['status' => DamageCase::STATUS_ESCALATED]);
                return redirect()->route('evaluator.index')->with('success', 'Case escalated to senior.');

            case 'physical':
                $case->update(['status' => DamageCase::STATUS_PHYSICAL_VISIT]);
                return redirect()->route('evaluator.index')->with('success', 'Case set to Require Physical Visit.');
        }

        return redirect()->back();
    }
}
