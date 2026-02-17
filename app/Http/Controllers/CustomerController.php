<?php

namespace App\Http\Controllers;

use App\Models\DamageCase;
use App\Models\CaseImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('customer.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'accident_number' => 'required|string|max:100|unique:cases,accident_number',
            'plate_number' => 'required|string|max:20',
            'images' => 'required|array|min:6',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'images.min' => 'Please upload at least 6 images (4 sides + damage area).',
        ]);

        $case = DamageCase::create([
            'accident_number' => $validated['accident_number'],
            'plate_number' => $validated['plate_number'],
            'status' => DamageCase::STATUS_SUBMITTED,
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('cases/' . $case->id, 'public');
            CaseImage::create([
                'case_id' => $case->id,
                'path' => $path,
            ]);
        }

        return redirect()->route('payment.show', $case)
            ->with('success', 'Case submitted successfully. Please pay the service fee.');
    }
}
