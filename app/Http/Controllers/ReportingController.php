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
        // Validasi input
        $request->validate([
            'reporter_as' => 'required|not_in:-- Pilih --', // Pastikan pilihan valid
            'case_type_id' => 'required|not_in:-- Pilih --',
            'case_description' => 'required|string|min:10',
            'chronology' => 'required|string|min:10',
            'reported_status_id' => 'required|not_in:-- Pilih --',
            'optional_phone_number' => 'nullable|digits_between:11,13|regex:/^[0-9]+$/',
            'optional_email' => 'nullable|email',
            'reporting_reasons' => 'required|array|min:1',
            'victim_requirements' => 'required|array|min:1'
        ], [
            'reporter_as.required' => 'Pilih peran pelapor.',
            'reporter_as.not_in' => 'Status pelapor tidak valid.',
            'case_type_id.required' => 'Pilih jenis kasus.',
            'case_type_id.not_in' => 'Jenis kasus tidak valid.',
            'reported_status_id.required' => 'Pilih status terlapor.',
            'reported_status_id.not_in' => 'Status terlapor tidak valid.',
            'case_description.required' => 'Deskripsi kasus wajib diisi.',
            'case_description.min' => 'Deskripsi kasus wajib diisi setidaknya 10 Karakter.',
            'chronology.required' => 'Cerita singkat peristiwa wajib diisi.',
            'chronology.min' => 'Cerita singkat peristiwa wajib diisi setidaknya 10 Karakter.',
            'optional_phone_number.digits_between' => 'Nomor telepon harus terdiri dari 11-13 digit.',
            'optional_phone_number.regex' => 'Nomor telepon harus terdiri dari angka.',
            'optional_email.email' => 'Alamat email tidak valid.',
            'reporting_reasons.required' => 'Alasan pengaduan wajib dipilih setidaknya 1',
            'victim_requirements.required' => 'Kebutuhan korban wajib dipilih setidaknya 1'
        ]);

        // Simpan data setelah validasi
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
        $title = 'Unduh Pengaduan';

        $reporting = $reporting->with(['reportingUser', 'reportingReason', 'reportedStatus', 'disabilityType', 'victimRequirement'])->first();

        $pdf = Pdf::loadView('reporting.show', compact('title', 'reporting'));
        return $pdf->stream();
    }

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

        return redirect()->route('reportings.all')->with('success', 'Status pengaduan berhasil diubah');
    }
}
