<?php

namespace App\Http\Controllers;

use App\Models\DamageCase;
use App\Models\CaseImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Step 1: Select appointment (date + time slot).
     */
    public function appointment(): View
    {
        return view('customer.appointment');
    }

    /**
     * Store selected appointment in session and redirect to form (Step 2).
     */
    public function storeAppointment(Request $request): RedirectResponse
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_slot' => 'required|string|max:50',
        ]);

        session([
            'customer_appointment_date' => $request->appointment_date,
            'customer_appointment_slot' => $request->appointment_slot,
        ]);

        return redirect()->route('customer.index')
            ->with('success', __('Appointment selected. Please fill in the accident details.'));
    }

    /**
     * Step 2: Accident form (accident number, plate, images).
     */
    public function index(Request $request): View|RedirectResponse
    {
        if (! session()->has('customer_appointment_date')) {
            return redirect()->route('customer.appointment')
                ->with('error', __('Please select an appointment first.'));
        }

        return view('customer.form', [
            'appointment_date' => session('customer_appointment_date'),
            'appointment_slot' => session('customer_appointment_slot'),
        ]);
    }

    /**
     * Store case and redirect to payment (Step 3).
     */
    public function store(Request $request): RedirectResponse
    {
        if (! session()->has('customer_appointment_date')) {
            return redirect()->route('customer.appointment')
                ->with('error', __('Please select an appointment first.'));
        }

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
            'appointment_date' => session('customer_appointment_date'),
            'appointment_slot' => session('customer_appointment_slot'),
            'status' => DamageCase::STATUS_SUBMITTED,
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('cases/' . $case->id, 'public');
            CaseImage::create([
                'case_id' => $case->id,
                'path' => $path,
            ]);
        }

        session()->forget(['customer_appointment_date', 'customer_appointment_slot']);

        return redirect()->route('payment.show', $case)
            ->with('success', 'Case submitted successfully. Please pay the service fee.');
    }
}
