<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Imports\AssetsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('asset_tag', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhere('specification', 'LIKE', "%{$search}%")
                  ->orWhere('company', 'LIKE', "%{$search}%")
                  ->orWhere('provider', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }

        // Order by latest first
        $assets = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get category status counts for all assets
        $allAssets = Asset::all();
        $categoryStatusCounts = [];
        $totalAssets = $allAssets->count();

        foreach ($allAssets as $asset) {
            $category = $asset->category;
            $status = $asset->status;
            
            if (!isset($categoryStatusCounts[$category])) {
                $categoryStatusCounts[$category] = [];
            }
            
            if (!isset($categoryStatusCounts[$category][$status])) {
                $categoryStatusCounts[$category][$status] = 0;
            }
            
            $categoryStatusCounts[$category][$status]++;
        }

        // Sort categories alphabetically
        ksort($categoryStatusCounts);

        return view('assets.index', compact(
            'assets',
            'categoryStatusCounts',
            'totalAssets'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company'        => 'required|in:NEBG,FA',
            'asset_tag'      => 'required|string|unique:assets,asset_tag|max:255',
            'delivery_date'  => 'nullable|date',
            'category'       => 'required|string|max:255',
            'brand'          => 'nullable|string|max:255',
            'provider'       => 'nullable|string|max:255',
            'status'         => 'required|string|max:255',
            'specification'  => 'nullable|string',
            'remarks'        => 'nullable|string',
        ]);

        Asset::create($validated);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'company'        => 'required|in:NEBG,FA',
            'asset_tag'      => 'required|string|max:255|unique:assets,asset_tag,' . $asset->id,
            'delivery_date'  => 'nullable|date',
            'category'       => 'required|string|max:255',
            'brand'          => 'nullable|string|max:255',
            'provider'       => 'nullable|string|max:255',
            'status'         => 'required|string|max:255',
            'specification'  => 'nullable|string',
            'remarks'        => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset deleted successfully!');
    }

    /**
     * Get asset details for AJAX modal.
     */
    public function details(Asset $asset)
    {
        return response()->json($asset);
    }
    
    /**
     * Show the import form.
     */
    public function importForm()
    {
        return view('assets.import');
    }

    /**
     * Import assets from Excel/CSV file.
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
            $import = new AssetsImport();
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getImportedCount();
            $failures = $import->failures();
            $failureCount = count($failures);

            // Build success message
            $message = "✅ Successfully imported {$importedCount} assets!";
            
            if ($failureCount > 0) {
                $message .= " ⚠️ {$failureCount} row(s) failed to import.";
                
                // Store failures in session
                session()->flash('import_failures', $failures);
            }

            return redirect()->route('assets.index')
                ->with('success', $message);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Handle validation exceptions from Excel
            $failures = $e->failures();
            
            return redirect()->back()
                ->with('error', 'Validation errors in the file.')
                ->with('import_failures', $failures);
            
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->back()
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV template for asset import.
     */
    public function downloadTemplate()
    {
        $headers = [
            'asset_tag', 
            'company', 
            'delivery_date', 
            'category', 
            'brand', 
            'provider', 
            'status', 
            'specification', 
            'remarks'
        ];

        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 support
            fputs($file, "\xEF\xBB\xBF");
            
            // Write headers
            fputcsv($file, $headers);
            
            // Add sample data row
            fputcsv($file, [
                'AVR1011', 
                'NEBG', 
                '2026-07-11', 
                'AVR', 
                'SECURE', 
                'ABC Corp', 
                'Good Condition In-Stock', 
                'AVR 1000VA', 
                'Office Use'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="asset_import_template.csv"',
        ]);
    }
}