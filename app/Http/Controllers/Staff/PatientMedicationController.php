<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\Patient;
use App\Models\PatientMedication;
use App\Models\PatientMedicationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientMedicationController extends Controller
{

    public function history()
    {
        $medications = PatientMedication::with([
            'patient.client',
            'items.item.category',
        ])->get();

        return view('staff.patient.patient-medication-record', compact('medications'));
    }

    public function index()
    {
        $patients = Patient::with('client')->get();

        $medicines = InventoryItem::where('category_id', 2)->get();
        $supplies  = InventoryItem::where('category_id', 3)->get();

        return view('staff.patient.patient-medication', compact('patients', 'medicines', 'supplies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patient,id',
            'items'            => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Create parent medication record
                $medication = PatientMedication::create([
                    'patient_id'    => $request->patient_id,
                    'notes'         => $request->remarks,
                    'prescribed_at' => now(),
                ]);

                // Loop through each medication item
                foreach ($request->items as $item) {
                    $inventoryItem = InventoryItem::findOrFail($item['item_id']);

                    // Check stock
                    if ($inventoryItem->quantity < $item['quantity']) {
                        throw new \Exception("Not enough stock for {$inventoryItem->item_name}");
                    }

                    // Deduct stock
                    $inventoryItem->decrement('quantity', $item['quantity']);

                    // Save patient medication item
                    PatientMedicationItem::create([
                        'patient_medication_id' => $medication->id,
                        'item_id'               => $item['item_id'],
                        'quantity'              => $item['quantity'],
                    ]);
                }
            });

            // Success redirect to history page
            return redirect()->route('patientMedication.history')->with('swal', [
                'icon'  => 'success',
                'title' => 'Success!',
                'text'  => 'Medication recorded successfully.',
            ]);

        } catch (\Exception $e) {
            // Error alert (including stock issues)
            return redirect()->back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Error!',
                'text'  => $e->getMessage(),
            ]);
        }
    }

}
