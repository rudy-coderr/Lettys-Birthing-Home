<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\Unit;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Show Medicines
     */
    public function medicines()
    {
        $medicines = InventoryItem::with(['category', 'unit'])
            ->whereHas('category', fn($q) => $q->where('name', 'medicine'))
            ->get();

        return view('admin.medication.medicine', compact('medicines'));
    }

    public function createMedicine()
    {
        $units    = Unit::all();
        $category = Category::where('name', 'medicine')->first();

        return view('admin.medication.add-medicine', compact('units', 'category'));
    }

    public function storeMedicine(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'quantity'      => 'required|integer|min:0',
            'unit_id'       => 'required|exists:units,id',
            'expiry_date'   => 'nullable|date',
            'reorder_level' => 'nullable|integer|min:0',
            'batch_no'      => 'nullable|string|max:50',
        ]);

        InventoryItem::create([
            'item_name'     => $validated['name'],
            'category_id'   => 2, // medicine category (assuming id=1)
            'batch_no'      => $validated['batch_no'] ?? null,
            'unit_id'       => $validated['unit_id'],
            'quantity'      => $validated['quantity'],
            'expiry_date'   => $validated['expiry_date'] ?? null,
            'reorder_level' => $validated['reorder_level'] ?? 10,
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Medicine Added!',
            'text'  => 'The medicine has been successfully added to the inventory.',
        ]);

        return redirect()->route('admin.inventory.medicines');
    }

    public function restock(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $medicine = InventoryItem::findOrFail($id);

        // add to existing stock
        $medicine->quantity += $validated['quantity'];
        $medicine->save();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Medicine Restocked!',
            'text'  => $validated['quantity'] . ' units added to ' . $medicine->item_name,
        ]);

        return redirect()->route('admin.inventory.medicines');
    }

    public function deleteMedicine($id)
    {
        $medicine     = InventoryItem::findOrFail($id);
        $medicineName = $medicine->item_name;
        $medicine->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Medicine Deleted!',
            'text'  => $medicineName . ' has been deleted from the inventory.',
        ]);

        return redirect()->route('admin.inventory.medicines');
    }

    public function showMedicine($id)
    {
        $medicine = InventoryItem::with('unit', 'category')->findOrFail($id);
        $units    = Unit::all();
        return view('admin.medication.view-medicine', compact('medicine', 'units'));
    }

    public function updateMedicine(Request $request, $id)
    {
        $medicine = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'item_name'     => 'required|string|max:150',
            'batch_no'      => 'nullable|string|max:50',
            'quantity'      => 'required|integer|min:0',
            'unit_id'       => 'required|exists:units,id',
            'expiry_date'   => 'nullable|date',
            'reorder_level' => 'nullable|integer|min:0',
        ]);

        $medicine->update($validated);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Medicine Updated!',
            'text'  => $medicine->item_name . ' has been successfully updated.',
        ]);

        return redirect()->route('admin.inventory.medicines', $medicine->id);
    }

    /**
     * Show Vaccines
     */
    public function vaccines()
    {
        $vaccines = InventoryItem::with(['category', 'unit'])
            ->whereHas('category', fn($q) => $q->where('name', 'vaccine'))
            ->get();

        return view('admin.medication.vaccine', compact('vaccines'));
    }

    public function createVaccine()
    {
        $units    = Unit::all();
        $category = Category::where('name', 'vaccine')->first();

        return view('admin.medication.add-vaccine', compact('units', 'category'));
    }

/**
 * Store Vaccine
 */
    public function storeVaccine(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'quantity'      => 'required|integer|min:0',
            'unit_id'       => 'required|exists:units,id',
            'expiry_date'   => 'nullable|date',
            'reorder_level' => 'nullable|integer|min:0',
            'batch_no'      => 'nullable|string|max:50',
        ]);

        InventoryItem::create([
            'item_name'     => $validated['name'],
            'category_id'   => 1,
            'batch_no'      => $validated['batch_no'] ?? null,
            'unit_id'       => $validated['unit_id'],
            'quantity'      => $validated['quantity'],
            'expiry_date'   => $validated['expiry_date'] ?? null,
            'reorder_level' => $validated['reorder_level'] ?? 10,
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Vaccine Added!',
            'text'  => 'The vaccine has been successfully added to the inventory.',
        ]);

        return redirect()->route('admin.inventory.vaccines');
    }

    public function restockVaccine(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $vaccine = InventoryItem::findOrFail($id);
        $vaccine->quantity += $validated['quantity'];
        $vaccine->save();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Vaccine Restocked!',
            'text'  => $validated['quantity'] . ' units added successfully.',
        ]);

        return redirect()->route('admin.inventory.vaccines');
    }

    public function deleteVaccine($id)
    {
        $vaccine     = InventoryItem::findOrFail($id);
        $vaccineName = $vaccine->item_name;
        $vaccine->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Vaccine Deleted!',
            'text'  => $vaccineName . ' has been deleted from the inventory.',
        ]);

        return redirect()->route('admin.inventory.vaccines');
    }

    public function showVaccine($id)
    {
        $vaccine = InventoryItem::with('unit', 'category')->findOrFail($id);
        $units   = Unit::all();
        return view('admin.medication.view-vaccine', compact('vaccine', 'units'));
    }

    public function updateVaccine(Request $request, $id)
    {
        $vaccine = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'item_name'     => 'required|string|max:150',
            'batch_no'      => 'nullable|string|max:50',
            'quantity'      => 'required|integer|min:0',
            'unit_id'       => 'required|exists:units,id',
            'expiry_date'   => 'nullable|date',
            'reorder_level' => 'nullable|integer|min:0',
        ]);

        $vaccine->update($validated);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Vaccine Updated!',
            'text'  => $vaccine->item_name . ' has been successfully updated.',
        ]);

        return redirect()->route('admin.inventory.vaccines');
    }

    /**
     * Show Supplies
     */
    public function supplies()
    {
        $supplies = InventoryItem::with(['category', 'unit'])
            ->whereHas('category', fn($q) => $q->where('name', 'supply'))
            ->get();

        return view('admin.medication.supply', compact('supplies'));
    }

    public function createSupply()
    {
        $units    = Unit::all();
        $category = Category::where('name', 'supply')->first();

        return view('admin.medication.add-supply', compact('units', 'category'));
    }
    public function storeSupply(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'quantity'      => 'required|integer|min:0',
            'unit_id'       => 'required|exists:units,id',
            'reorder_level' => 'nullable|integer|min:0',
        ]);

        InventoryItem::create([
            'item_name'     => $validated['name'],
            'category_id'   => 3,
            'unit_id'       => $validated['unit_id'],
            'quantity'      => $validated['quantity'],
            'reorder_level' => $validated['reorder_level'] ?? 10,
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Supply Added!',
            'text'  => 'The supply has been successfully added to the inventory.',
        ]);

        return redirect()->route('admin.inventory.supplies');
    }
    public function restockSupply(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $supply = InventoryItem::findOrFail($id);
        $supply->quantity += $validated['quantity'];
        $supply->save();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Supply Restocked!',
            'text'  => $validated['quantity'] . ' units added successfully.',
        ]);

        return redirect()->route('admin.inventory.supplies');
    }

    public function deleteSupply($id)
    {
        $supply     = InventoryItem::findOrFail($id);
        $supplyName = $supply->item_name;
        $supply->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Supply Deleted!',
            'text'  => $supplyName . ' has been deleted from the inventory.',
        ]);

        return redirect()->route('admin.inventory.supplies');
    }

    public function showSupply($id)
    {
        $supply = InventoryItem::with('unit', 'category')->findOrFail($id);
        $units  = Unit::all();

        return view('admin.medication.view-supply', compact('supply', 'units'));
    }

    public function updateSupply(Request $request, $id)
    {
        $supply = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'item_name'     => 'required|string|max:150',
            'quantity'      => 'required|integer|min:0',
            'unit_id'       => 'required|exists:units,id',
            'reorder_level' => 'nullable|integer|min:0',
        ]);

        $supply->update($validated);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Supply Updated!',
            'text'  => $supply->item_name . ' has been successfully updated.',
        ]);

        return redirect()->route('admin.inventory.supplies');
    }

    public function storeUnit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:units,name',
        ]);

        Unit::create($validated);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Unit Added!',
            'text'  => $validated['name'] . ' has been successfully added.',
        ]);

        return redirect()->back();
    }

}
