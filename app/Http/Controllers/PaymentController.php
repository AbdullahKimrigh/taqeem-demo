<?php

namespace App\Http\Controllers;

use App\Models\DamageCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(DamageCase $case): View
    {
        return view('payment.show', compact('case'));
    }

    public function process(Request $request, DamageCase $case): RedirectResponse
    {
        $case->update([
            'service_fee_paid' => true,
            'status' => DamageCase::STATUS_READY,
        ]);

        return redirect()->route('evaluator.index')
            ->with('success', 'Payment successful. Case is now ready for assessment.');
    }
}
