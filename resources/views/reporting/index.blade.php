@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title w-100">
                    <h4 class="card-title d-inline-block mb-0">Pengaduan</h4>
                    <select class="form-control mt-2" id="select-reporting-status-filter" name="filter">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Pengaduan</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                    </select>
                </div>
            </div>

            <div class="card-body">
                <table id="datatable" class="table data-table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu (WIB)</th>
                            <th>Nama Pelapor</th>
                            <th>Jenis Kasus</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportings as $reporting)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $reporting->created_at->format('d-m-Y') }}</td>
                            <td>{{ $reporting->created_at->format('H:i:s') }}</td>
                            <td>{{ $reporting->reportingUser->name }}</td>
                            <td>{{ $reporting->caseType->name }}</td>
                            <td>
                                @if ($reporting->reporting_status == 'published')
                                <span class="btn btn-success btn-sm">{{ ucfirst($reporting->reporting_status) }}</span>
                                @elseif ($reporting->reporting_status == 'archived')
                                <span class="btn btn-danger btn-sm">{{ ucfirst($reporting->reporting_status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('reportings.show') }}" class="btn btn-warning btn-sm mr-2">
                                    <i class="ri-eye-line"></i> Detail
                                </a>
                                <a href="{{ route('reportings.progress') }}" class="btn btn-primary btn-sm mr-2">
                                    <i class="ri-line-chart-line"></i> Progress
                                </a>
                                @if ($reporting->reporting_status == 'published')
                                <a href="#" class="btn btn-danger btn-sm mr-2">
                                    <i class="ri-inbox-archive-line"></i> Arsipkan
                                </a>
                                @elseif ($reporting->reporting_status == 'archived')
                                <a href="#" class="btn btn-success btn-sm mr-2">
                                    <i class="ri-inbox-unarchive-line"></i> Publikasikan
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function() {
        $('#select-reporting-status-filter').change(e => {
            window.location.href = `{{ route("reportings.all") }}${$(e.target).val() ? `?status=${$(e.target).val()}` : ''}`;
        });
    });
</script>
@endsection