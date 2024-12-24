<?php

namespace App\Http\Controllers;

use App\Models\CaseType;
use App\Models\DisabilityType;
use App\Models\Reporting;
use App\Models\ReportedStatus;
use App\Models\ReportingProgress;
use App\Models\ReportingReason;
use App\Models\VictimRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Pengaduan';
        $status = $request->input('status');

        if ($status == 'published') {
            $reportings = Reporting::where('reporting_status', 'published')->with(['reportingUser', 'caseType'])->get();
        } elseif ($status == 'archived') {
            $reportings = Reporting::where('reporting_status', 'archived')->with(['reportingUser', 'caseType'])->get();
        } else {
            $reportings = Reporting::with(['reportingUser', 'caseType'])->get();
        }

        return view('reporting.index', compact('title', 'reportings', 'status'));
    }

    public function indexReportingUser()
    {
        $title = 'Pengaduan';

        $user = Auth::user();
        $reportings = Reporting::where('reporter_id', $user->id)->with('caseType')->get();

        return view('reporting.index-user', compact('title', 'reportings'));
    }

    public function create()
    {
        $title = 'Tambah Pengaduan';

        $caseTypes = CaseType::all();
        $reportedStatuses = ReportedStatus::all();
        $disabilityTypes = DisabilityType::all();
        $reportingReasons = ReportingReason::all();
        $victimRequirements = VictimRequirement::all();

        return view('reporting.create', compact('title', 'caseTypes', 'reportedStatuses', 'disabilityTypes', 'reportingReasons', 'victimRequirements'));
    }

    public function store(Request $request)
    {
        $reporting = new Reporting();
        $reporting->reporter_id = Auth::id();
        $reporting->reporter_as = $request->input('reporter_as');
        $reporting->case_type_id = $request->input('case_type_id');
        $reporting->case_description = $request->input('case_description');
        $reporting->chronology = $request->input('chronology');
        $reporting->reported_status_id = $request->input('reported_status_id');
        $reporting->optional_disability_type = $request->input('optional_disability_type');
        $reporting->optional_reporting_reason = $request->input('optional_reporting_reason');
        $reporting->optional_phone_number = $request->input('optional_phone_number');
        $reporting->optional_email = $request->input('optional_email');
        $reporting->optional_victim_requirement = $request->input('optional_victim_requirement');
        $reporting->save();

        // Pivot table
        $reporting->disabilityType()->attach($request->input('disability_types', []));
        $reporting->reportingReason()->attach($request->input('reporting_reasons', []));
        $reporting->victimRequirement()->attach($request->input('victim_requirements', []));

        return redirect()->route('reportings.user')->with('success', 'Pengaduan berhasil ditambahkan');
    }

    public function show(Reporting $reporting)
    {
        $title = 'Laporan Pengaduan';

        $pdf = Pdf::loadView('reporting.show', compact('title', 'reporting'));
        return $pdf->stream();
    }

    // public function indexReportingProgress(Reporting $reporting)
    // {
    //     $title = 'Progress Pengaduan';

    //     $reportingProgress = ReportingProgress::where('reporting_id', $reporting->id)->get();

    //     return view('reporting.index-progress', compact(
    //         'title',
    //         'reporting',
    //         'reportingProgress'
    //     ));
    // }

    public function indexReportingProgress($id)
    {
        $title = 'Progress Pengaduan';

        $reporting = Reporting::findOrFail($id);

        $reportingProgress = ReportingProgress::where('reporting_id', $id)->get();

        return view('reporting.index-progress', compact(
            'title',
            'reporting',
            'reportingProgress',
        ));
    }

    public function storeReportingProgress(Request $request)
    {
        $request->validate([
            'reporting_id' => 'required',
            'note' => 'required|string',
        ]);

        $progress = new ReportingProgress();
        $progress->reporting_id = $request->input('reporting_id');
        $progress->note = $request->input('note');
        $progress->save();

        return redirect()->route('reportings.progress', ['id' => $request->reporting_id])->with('success', 'Progress berhasil ditambahkan');
    }

    public function updateReportingStatus(Request $request, $id)
    {
        $reporting = Reporting::findOrFail($id);

        if ($request->status == 'archive') {
            $reporting->reporting_status = 'archived';
        } elseif ($request->status == 'publish') {
            $reporting->reporting_status = 'published';
        }

        $reporting->save();

        return redirect()->route('reportings.index')->with('success', 'Status pengaduan berhasil diubah');
    }
}
