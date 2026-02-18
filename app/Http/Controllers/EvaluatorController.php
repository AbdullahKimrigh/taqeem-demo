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

        $case->update(['notes' => $request->input('notes', '')]);

        if ($request->filled('damages') && is_array($request->damages)) {
            $damages = [];
            foreach ($request->damages as $d) {
                $part = trim((string) ($d['part'] ?? ''));
                $action = trim((string) ($d['action'] ?? ''));
                $cost = (float) ($d['cost'] ?? 0);
                if ($part !== '' || $action !== '' || $cost > 0) {
                    $damages[] = [
                        'part' => $part ?: '-',
                        'action' => $action ?: '-',
                        'cost' => (int) round($cost),
                    ];
                }
            }
            $total = array_sum(array_column($damages, 'cost'));
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
                    ->with('success', 'Case approved. Report generated.')
                    ->with('action_popup', __('The evaluation report was issued and sent to the customer.'));

            case 'more_images':
                $case->update(['status' => DamageCase::STATUS_NEEDS_IMAGES]);
                return redirect()->route('evaluator.index')
                    ->with('success', 'Case set to Needs More Images.')
                    ->with('action_popup', __('The link for uploading more pictures was sent to the customer.'));

            case 'escalate':
                $case->update(['status' => DamageCase::STATUS_ESCALATED]);
                return redirect()->route('evaluator.index')
                    ->with('success', 'Case escalated to senior.')
                    ->with('action_popup', __('The senior was notified!'));

            case 'physical':
                $case->update(['status' => DamageCase::STATUS_PHYSICAL_VISIT]);
                return redirect()->route('evaluator.index')
                    ->with('success', 'Case set to Require Physical Visit.')
                    ->with('action_popup', __('The customer was notified to physically visit the center based on their selected appointment.'));
        }

        return redirect()->back();
    }
}
