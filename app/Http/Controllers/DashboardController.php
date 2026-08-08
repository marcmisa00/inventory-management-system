<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index()
    {
        $employees = DB::connection('hris')
            ->table('employee_profile as ep')
            ->join('employee_details as ed', 'ep.idno', '=', 'ed.idno')
            ->join('jobtitle as jt', 'ed.designation', '=', 'jt.id')
            ->join('department as d', 'ed.department', '=', 'd.id')
            ->select(
                'ep.idno',
                'ep.lastname',
                'ep.firstname',
                'ep.address',
                'jt.jobtitle as designation',
                'ed.company',
                'ed.location',
                'ed.work_area',
                'd.department as department'
            )
            ->limit(10)
            ->get();
         return view('dashboard', compact('employees'));
    }
}