<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Wakaf;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        $report = Wakaf::with('transaction')->where('status', 'active')->get();
        return view('admin.report.report', compact(['report']));
    }

    public function generateReport(Request $request)
    {
        $report = Wakaf::with('transaction')->where('status', 'active')->get();
        $pdf = PDF::loadView('admin.report.generate-pdf', compact(['report']));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('laporan-program-wakaf-' . time());
    }
}
