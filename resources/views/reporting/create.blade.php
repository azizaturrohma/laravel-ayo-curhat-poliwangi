@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="row mx-1 my-1">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title w-100">
                    <h4 class="card-title d-inline-block mb-0">Tambah Pengaduan</h4>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('reportings.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Status Pelapor</label>
                        <select class="form-control mb-3" name="reporter_as" required>
                            <option selected="">-- Pilih --</option>
                            <option value="saksi">Saksi</option>
                            <option value="korban">Korban</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kasus</label>
                        <select class="form-control mb-3" name="case_type_id" required>
                            <option selected="">-- Pilih --</option>
                            @foreach($caseTypes as $caseType)
                            <option value="{{ $caseType->id }}">{{ $caseType->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="case_description">Deskripsi Kasus</label>
                        <textarea class="form-control" id="case_description" name="case_description" rows="1" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="chronology">Cerita Singkat Peristiwa</label>
                        <textarea class="form-control" id="chronology" name="chronology" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Status Terlapor</label>
                        <select class="form-control mb-3" name="reported_status_id" required>
                            <option selected="">-- Pilih --</option>
                            @foreach($reportedStatuses as $reportedStatus)
                            <option value="{{ $reportedStatus->id }}">{{ $reportedStatus->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jenis Disabilitas (Jika Memiliki Disabilitas)</label>
                        <div class="form-control p-3" style="height:auto;">
                            @foreach($disabilityTypes as $disabilityType)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="disability_types[]" value="{{ $disabilityType->id }}" required>
                                <label class="form-check-label">{{ $disabilityType->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="optional_disability_type">Disabilitas Lainnya (Jika Ada)</label>
                        <input type="text" class="form-control" id="optional_disability_type" name="optional_disability_type">
                    </div>

                    <div class="form-group">
                        <label>Alasan Pengaduan</label>
                        <div class="form-control p-3" style="height:auto;">
                            @foreach($reportingReasons as $reportingReason)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="reporting_reasons[]" value="{{ $reportingReason->id }}" required>
                                <label class="form-check-label">{{ $reportingReason->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="optional_reporting_reason">Alasan Lainnya (Jika Ada)</label>
                        <input type="text" class="form-control" id="optional_reporting_reason" name="optional_reporting_reason">
                    </div>

                    <div class="form-group">
                        <label for="optional_phone_number">Nomor Telepon Pihak Lain Yang Dapat Dikonfirmasi (Jika Ada)</label>
                        <input type="text" class="form-control" id="optional_phone_number" name="optional_phone_number">
                    </div>

                    <div class="form-group">
                        <label for="optional_email">Email Pihak Lain Yang Dapat Dikonfirmasi (Jika Ada)</label>
                        <input type="email" class="form-control" id="optional_email" name="optional_email">
                    </div>

                    <div class="form-group">
                        <label>Identifikasi Kebutuhan Korban</label>
                        <div class="form-control p-3" style="height:auto;">
                            @foreach($victimRequirements as $victimRequirement)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="victim_requirements[]" value="{{ $victimRequirement->id }}" required>
                                <label class="form-check-label">{{ $victimRequirement->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="optional_victim_requirement">Identifikasi Kebutuhan Lainnya (Jika Ada)</label>
                        <input type="text" class="form-control" id="optional_victim_requirement" name="optional_victim_requirement">
                    </div>

                    <button type="submit" id="add-reporting" class="btn btn-primary">Kirim</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection