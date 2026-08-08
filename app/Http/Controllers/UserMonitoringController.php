<?php

namespace App\Http\Controllers;

use App\Models\UserMonitoring;
use App\Models\Asset;
use App\Models\PurchaseTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = UserMonitoring::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pc_name', 'LIKE', "%{$search}%")
                  ->orWhere('serial_number', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhere('department', 'LIKE', "%{$search}%")
                  ->orWhere('idno', 'LIKE', "%{$search}%");
            });
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Company filter
        if ($request->filled('company')) {
            $query->where('company', $request->company);
        }

        // Order by latest first
        $userMonitoring = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get stats
        $totalRecords = UserMonitoring::count();
        $assignedCount = UserMonitoring::whereNotNull('idno')->count();
        $departmentCount = UserMonitoring::whereNotNull('department')->distinct('department')->count('department');
        $totalCost = UserMonitoring::sum('pc_cost');

        // Get unique departments for filter
        $departments = UserMonitoring::whereNotNull('department')
            ->distinct('department')
            ->pluck('department')
            ->toArray();

        return view('user-monitoring.index', compact(
            'userMonitoring',
            'totalRecords',
            'assignedCount',
            'departmentCount',
            'totalCost',
            'departments'
        ));
    }

        public function create()
        {
            // Fetch employees from HRIS database
            $employees = DB::connection('hris')
                ->table('employee_profile as ep')
                ->join('employee_details as ed', 'ep.idno', '=', 'ed.idno')
                ->join('jobtitle as jt', 'ed.designation', '=', 'jt.id')
                ->join('department as d', 'ed.department', '=', 'd.id')
                ->where('ed.status','!=','RESIGNED')
                ->select(
                    'ep.idno',
                    'ep.lastname',
                    'ep.firstname',
                    'ep.middlename',
                    'ep.address',
                    'jt.jobtitle as designation',
                    'ed.company',
                    'ed.location',
                    'ed.work_area',
                    'd.department as department'
                )
                ->orderBy('ep.lastname', 'asc')
                ->get();

            // Fetch assets grouped by category
            // Only show assets with status 'Good Condition In-Stock' or 'Good Condition In-Use'
            $assetsByCategory = Asset::select('id', 'asset_tag', 'category', 'brand', 'specification', 'status')
                ->whereIn('status', ['Good Condition In-Stock'])
                ->orderBy('category')
                ->orderBy('asset_tag')
                ->get()
                ->groupBy('category');

            // Debug: Check if any assets are found
            \Log::info('Assets found:', ['count' => $assetsByCategory->count(), 'categories' => $assetsByCategory->keys()->toArray()]);

            return view('user-monitoring.create', compact('employees', 'assetsByCategory'));
        }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pc_name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:user_monitoring',
            'purchase_tracker_id' => 'nullable|exists:purchase_trackers,id',
            'idno' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'set_up' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'motherboard' => 'nullable|string|max:255',
            'processor' => 'nullable|string|max:255',
            'hdd' => 'nullable|string|max:255',
            'ssd' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'psu' => 'nullable|string|max:255',
            'cpuf' => 'nullable|string|max:255',
            'monitor' => 'nullable|string|max:255',
            'keyboard' => 'nullable|string|max:255',
            'mouse' => 'nullable|string|max:255',
            'avr' => 'nullable|string|max:255',
            'binaural' => 'nullable|string|max:255',
            'monaural' => 'nullable|string|max:255',
            'magic_jack' => 'nullable|string|max:255',
            'headset' => 'nullable|string|max:255',
            'camera' => 'nullable|string|max:255',
            'dialpad' => 'nullable|string|max:255',
            'ups' => 'nullable|string|max:255',
            'telephone_set' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'vpn' => 'nullable|string|max:255',
            'operating_system' => 'nullable|string|max:255',
            'product_key' => 'nullable|string|max:255',
            'microsoft_office' => 'nullable|string|max:255',
            'office_serial_key' => 'nullable|string|max:255',
            'microsoft_account' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
            'pc_cost' => 'nullable|numeric|min:0',
            'store' => 'nullable|string|max:255',
        ]);

        UserMonitoring::create($validated);

        return redirect()
            ->route('user-monitoring.index')
            ->with('success', 'PC user monitoring record created successfully!');
    }

    public function show(UserMonitoring $userMonitoring)
    {
        return view('user-monitoring.show', compact('userMonitoring'));
    }

    public function edit(UserMonitoring $userMonitoring)
    {
        // Fetch employees from HRIS database
        $employees = DB::connection('hris')
            ->table('employee_profile as ep')
            ->join('employee_details as ed', 'ep.idno', '=', 'ed.idno')
            ->join('jobtitle as jt', 'ed.designation', '=', 'jt.id')
            ->join('department as d', 'ed.department', '=', 'd.id')
            ->where('ed.status','!=','RESIGNED')
            ->select(
                'ep.idno',
                'ep.lastname',
                'ep.firstname',
                'ep.middlename',
                'ep.address',
                'jt.jobtitle as designation',
                'ed.company',
                'ed.location',
                'ed.work_area',
                'd.department as department'
            )
            ->orderBy('ep.lastname', 'asc')
            ->get();

        // Fetch assets grouped by category
        $assetsByCategory = Asset::select('id', 'asset_tag', 'category', 'brand', 'specification', 'status')
            ->whereIn('status', ['New', 'Old', 'For Sale'])
            ->orderBy('category')
            ->orderBy('asset_tag')
            ->get()
            ->groupBy('category');

        // Fetch purchase trackers
        $purchaseTrackers = PurchaseTracker::select('id', 'purachase_name', 'receipt_number', 'receipt_date', 'grand_total')
            ->orderBy('purachase_name', 'asc')
            ->get();

        return view('user-monitoring.edit', compact('userMonitoring', 'employees', 'assetsByCategory', 'purchaseTrackers'));
    }

    public function update(Request $request, UserMonitoring $userMonitoring)
    {
        $validated = $request->validate([
            'pc_name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:user_monitoring,serial_number,' . $userMonitoring->id,
            'purchase_tracker_id' => 'nullable|exists:purchase_trackers,id',
            'idno' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'set_up' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'motherboard' => 'nullable|string|max:255',
            'processor' => 'nullable|string|max:255',
            'hdd' => 'nullable|string|max:255',
            'ssd' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'psu' => 'nullable|string|max:255',
            'cpuf' => 'nullable|string|max:255',
            'monitor' => 'nullable|string|max:255',
            'keyboard' => 'nullable|string|max:255',
            'mouse' => 'nullable|string|max:255',
            'avr' => 'nullable|string|max:255',
            'binaural' => 'nullable|string|max:255',
            'monaural' => 'nullable|string|max:255',
            'magic_jack' => 'nullable|string|max:255',
            'headset' => 'nullable|string|max:255',
            'camera' => 'nullable|string|max:255',
            'dialpad' => 'nullable|string|max:255',
            'ups' => 'nullable|string|max:255',
            'telephone_set' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'vpn' => 'nullable|string|max:255',
            'operating_system' => 'nullable|string|max:255',
            'product_key' => 'nullable|string|max:255',
            'microsoft_office' => 'nullable|string|max:255',
            'office_serial_key' => 'nullable|string|max:255',
            'microsoft_account' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
            'pc_cost' => 'nullable|numeric|min:0',
            'store' => 'nullable|string|max:255',
        ]);

        $userMonitoring->update($validated);

        return redirect()
            ->route('user-monitoring.index')
            ->with('success', 'PC user monitoring record updated successfully!');
    }

    public function destroy(UserMonitoring $userMonitoring)
    {
        $userMonitoring->delete();

        return redirect()
            ->route('user-monitoring.index')
            ->with('success', 'PC user monitoring record deleted successfully!');
    }
}