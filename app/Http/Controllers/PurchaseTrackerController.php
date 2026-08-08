<?php

namespace App\Http\Controllers;

use App\Models\PurchaseTracker;
use App\Models\PurchaseTrackerItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PurchaseTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PurchaseTracker::query();

        // Search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Company filter
        if ($request->filled('company')) {
            $query->company($request->company);
        }

        // Date range filter
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $query->dateRange($request->date_from, $request->date_to);
        }

        // Order by latest first
        $purchases = $query->orderBy('purchase_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(10)
                          ->withQueryString();

        // Stats (from all records, not just paginated)
        $totalPurchases = PurchaseTracker::count();
        $nebgCount = PurchaseTracker::where('company', 'NEBG')->count();
        $faCount = PurchaseTracker::where('company', 'FA')->count();
        $nebgAmount = PurchaseTracker::where('company', 'NEBG')->sum('grand_total');
        $faAmount = PurchaseTracker::where('company', 'FA')->sum('grand_total');
        $totalAmount = PurchaseTracker::sum('grand_total');

        return view('purchaseTracker.index', compact(
            'purchases',
            'totalPurchases',
            'nebgCount',
            'faCount',
            'nebgAmount',
            'faAmount',
            'totalAmount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchaseTracker.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the main purchase data
        $validator = Validator::make($request->all(), [
            'purachase_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'company' => ['required', Rule::in(['NEBG', 'FA'])],
            'vendor' => 'required|string|max:255',
            'receipt_number' => 'nullable|string|unique:purchase_trackers,receipt_number|max:255',
            'receipt_date' => 'nullable|date',
            'receipt_details' => 'nullable|string',
            'grand_total' => 'required|numeric|min:0|max:9999999999.99',
            'remarks' => 'nullable|string',
            'receipt_encoded_by' => 'required|string|max:255',
            'received_by' => 'nullable|string|max:255',
            'pickup_by' => 'nullable|string|max:255',
            'bought_by' => 'nullable|string|max:255',
            
            // Validate items array
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('purchaseTracker.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Prepare purchase data
            $purchaseData = $request->only([
                'purachase_name',
                'purchase_date',
                'company',
                'vendor',
                'receipt_number',
                'receipt_date',
                'receipt_details',
                'grand_total',
                'remarks',
                'receipt_encoded_by',
                'received_by',
                'pickup_by',
                'bought_by'
            ]);

            // Create the purchase tracker
            $purchase = PurchaseTracker::create($purchaseData);

            // Create purchase items
            $items = [];
            foreach ($request->items as $itemData) {
                $items[] = new PurchaseTrackerItem([
                    'description' => $itemData['description'],
                    'unit_price' => $itemData['unit_price'],
                    'quantity' => $itemData['quantity'],
                    'subtotal' => $itemData['subtotal'],
                ]);
            }

            // Save all items at once
            $purchase->items()->saveMany($items);

            DB::commit();

            return redirect()
                ->route('purchaseTracker.index')
                ->with('success', 'Purchase record created successfully with ' . count($items) . ' items!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('purchaseTracker.create')
                ->with('error', 'Failed to create purchase. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseTracker $purchaseTracker)
    {
        // Load items relationship
        $purchaseTracker->load('items');
        return view('purchaseTracker.show', compact('purchaseTracker'));
    }

    /**
     * Show the form for editing the specified resource.
     */
      public function edit($id)
    {
        $purchase = PurchaseTracker::with('items')->findOrFail($id);
        return view('purchaseTracker.edit', compact('purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'purachase_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'company' => 'required|in:NEBG,FA',
            'vendor' => 'required|string|max:255',
            'receipt_number' => 'nullable|string|max:100',
            'receipt_date' => 'nullable|date',
            'receipt_details' => 'nullable|string',
            'receipt_encoded_by' => 'nullable|string|max:100',
            'received_by' => 'nullable|string|max:100',
            'pickup_by' => 'nullable|string|max:100',
            'bought_by' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
            'grand_total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Find the purchase
            $purchase = PurchaseTracker::findOrFail($id);

            // Update purchase
            $purchase->update([
                'purachase_name' => $validated['purachase_name'],
                'purchase_date' => $validated['purchase_date'],
                'company' => $validated['company'],
                'vendor' => $validated['vendor'],
                'receipt_number' => $validated['receipt_number'] ?? null,
                'receipt_date' => $validated['receipt_date'] ?? null,
                'receipt_details' => $validated['receipt_details'] ?? null,
                'receipt_encoded_by' => $validated['receipt_encoded_by'] ?? null,
                'received_by' => $validated['received_by'] ?? null,
                'pickup_by' => $validated['pickup_by'] ?? null,
                'bought_by' => $validated['bought_by'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'grand_total' => $validated['grand_total'],
            ]);

            // Delete existing items
            $purchase->items()->delete();

            // Create new items
            foreach ($validated['items'] as $itemData) {
                $purchase->items()->create([
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $itemData['subtotal'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('purchaseTracker.index')
                ->with('success', 'Purchase updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update purchase: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseTracker $purchaseTracker)
    {
        try {
            DB::beginTransaction();
            
            // Delete associated items first (cascade will handle this if set in migration)
            $purchaseTracker->items()->delete();
            
            // Delete the purchase
            $purchaseTracker->delete();
            
            DB::commit();

            return redirect()
                ->route('purchaseTracker.index')
                ->with('success', 'Purchase record and all associated items deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('purchaseTracker.index')
                ->with('error', 'Failed to delete purchase. Error: ' . $e->getMessage());
        }
    }

    public function showModal($id)
            {
                $purchase = PurchaseTracker::with('items')->findOrFail($id);
                return response()->json($purchase);
            }


              public function importForm()
    {
        return view('purchaseTracker.import');
    }

    /**
     * Import purchase tracker data from Excel/CSV file.
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $import = new PurchaseTrackerImport();
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getImportedCount();
            $failures = $import->failures();
            $failureCount = count($failures);

            DB::commit();

            $message = "✅ Successfully imported {$importedCount} purchase items!";
            
            if ($failureCount > 0) {
                $message .= " ⚠️ {$failureCount} row(s) failed to import.";
                session()->flash('import_failures', $failures);
            }

            return redirect()->route('purchaseTracker.index')
                ->with('success', $message);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            
            return redirect()->back()
                ->with('error', 'Validation errors in the file.')
                ->with('import_failures', $failures);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV template for purchase tracker import.
     */
    public function downloadTemplate()
    {
        $headers = [
            'purchase_name',
            'purchase_date',
            'company',
            'vendor',
            'receipt_number',
            'receipt_date',
            'receipt_details',
            'remarks',
            'receipt_encoded_by',
            'received_by',
            'pickup_by',
            'bought_by',
            'item_description',
            'unit_price',
            'quantity',
        ];

        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $headers);
            
            // Sample data - multiple rows for the same purchase
            fputcsv($file, [
                'PO-2024-001',
                '2024-01-15',
                'NEBG',
                'Tech Supplier Inc.',
                'RCP-001',
                '2024-01-20',
                'Office equipment purchase',
                'For IT department',
                'John Doe',
                'Jane Smith',
                'Mike Johnson',
                'Sarah Wilson',
                'Dell XPS 15 Laptop',
                '1500.00',
                '5',
            ]);
            
            fputcsv($file, [
                'PO-2024-001',
                '2024-01-15',
                'NEBG',
                'Tech Supplier Inc.',
                'RCP-001',
                '2024-01-20',
                'Office equipment purchase',
                'For IT department',
                'John Doe',
                'Jane Smith',
                'Mike Johnson',
                'Sarah Wilson',
                'Samsung 27" 4K Monitor',
                '800.00',
                '3',
            ]);
            
            fputcsv($file, [
                'PO-2024-002',
                '2024-02-01',
                'FA',
                'Office Supply Co.',
                'RCP-002',
                '2024-02-05',
                'Office furniture',
                'For new employees',
                'John Doe',
                'Jane Smith',
                'Mike Johnson',
                'Sarah Wilson',
                'Office Chair Ergonomic',
                '350.00',
                '10',
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="purchase_tracker_template.csv"',
        ]);
    }

    /**
     * Get purchase tracker details for AJAX modal.
     */
    public function details(PurchaseTracker $purchaseTracker)
    {
        $purchaseTracker->load('items');
        return response()->json($purchaseTracker);
    }


}