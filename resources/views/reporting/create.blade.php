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
                    <form action="{{ route('reportings.store') }}" method="POST" id="counseling-form">
                        @csrf
                        <div class="form-group">
                            <label>Status Pelapor</label>
                            <select class="form-control mb-3" name="reporter_as">
                                <option selected="">-- Pilih --</option>
                                <option value="saksi">Saksi</option>
                                <option value="korban">Korban</option>
                            </select>
                            @error('reporter_as')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Jenis Kasus</label>
                            <select class="form-control mb-3" name="case_type_id">
                                <option selected="">-- Pilih --</option>
                                @foreach ($caseTypes as $caseType)
                                    <option value="{{ $caseType->id }}">{{ $caseType->name }}</option>
                                @endforeach
                            </select>
                            @error('case_type_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="case_description">Deskripsi Kasus</label>
                            <textarea class="form-control" id="case_description" name="case_description" rows="1"></textarea>
                            @error('case_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="chronology">Cerita Singkat Peristiwa</label>
                            <textarea class="form-control" id="chronology" name="chronology" rows="3"></textarea>
                            @error('chronology')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Status Terlapor</label>
                            <select class="form-control mb-3" name="reported_status_id">
                                <option selected="">-- Pilih --</option>
                                @foreach ($reportedStatuses as $reportedStatus)
                                    <option value="{{ $reportedStatus->id }}">{{ $reportedStatus->name }}</option>
                                @endforeach
                            </select>
                            @error('reported_status_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Jenis Disabilitas (Jika Memiliki Disabilitas)</label>
                            <div class="form-control p-3" style="height:auto;">
                                @foreach ($disabilityTypes as $disabilityType)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="disability_types[]"
                                            value="{{ $disabilityType->id }}">
                                        <label class="form-check-label">{{ $disabilityType->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('disability_types')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="optional_disability_type">Disabilitas Lainnya (Jika Ada)</label>
                            <input type="text" class="form-control" id="optional_disability_type"
                                name="optional_disability_type">
                            @error('optional_disability_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Alasan Pengaduan</label>
                            <div class="form-control p-3" style="height:auto;">
                                @foreach ($reportingReasons as $reportingReason)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reporting_reasons[]"
                                            value="{{ $reportingReason->id }}">
                                        <label class="form-check-label">{{ $reportingReason->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('reporting_reasons')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="optional_reporting_reason">Alasan Lainnya (Jika Ada)</label>
                            <input type="text" class="form-control" id="optional_reporting_reason"
                                name="optional_reporting_reason">
                            @error('optional_reporting_reason')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="optional_phone_number">Nomor Telepon Pihak Lain Yang Dapat Dikonfirmasi (Jika
                                Ada)</label>
                            <input type="text" class="form-control" id="optional_phone_number"
                                name="optional_phone_number">
                            @error('optional_phone_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="optional_email">Email Pihak Lain Yang Dapat Dikonfirmasi (Jika Ada)</label>
                            <input type="email" class="form-control" id="optional_email" name="optional_email">
                            @error('optional_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Identifikasi Kebutuhan Korban</label>
                            <div class="form-control p-3" style="height:auto;">
                                @foreach ($victimRequirements as $victimRequirement)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="victim_requirements[]"
                                            value="{{ $victimRequirement->id }}">
                                        <label class="form-check-label">{{ $victimRequirement->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('victim_requirements')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="optional_victim_requirement">Identifikasi Kebutuhan Lainnya (Jika Ada)</label>
                            <input type="text" class="form-control" id="optional_victim_requirement"
                                name="optional_victim_requirement">
                            @error('optional_victim_requirement')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#counseling-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log("Submit ditangkap!");
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Pastikan data anda sudah benar!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, kirim!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        } else {
            console.error("#counseling-form tidak ditemukan");
        }

        // Alert success jika ada session success
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    });
</script>
