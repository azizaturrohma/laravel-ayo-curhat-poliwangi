@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="row mx-1 my-1">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title w-100">
                        <h4 class="card-title d-inline-block mb-0">Data Petugas</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-10">
                            <select class="form-control d-inline-block" id="select-user-status-filter" name="filter">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Petugas
                                </option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            @can('create_users')
                                <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                                    data-target="#createUserModal">
                                    <i class="ri-user-add-line"></i>
                                    Tambah Data
                                </button>

                                <!-- Modal -->
                                @include('user.create')
                            @endcan
                        </div>
                    </div>

                    <table id="datatable" class="table data-table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Nomor Telepon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number }}</td>
                                    <td>
                                        @if ($user->user_status == 'active')
                                            <span class="btn btn-success btn-sm">{{ ucfirst($user->user_status) }}</span>
                                        @elseif ($user->user_status == 'inactive')
                                            <span class="btn btn-danger btn-sm">{{ ucfirst($user->user_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('update_users')
                                            <!-- Detail & Edit -->
                                            <a href="{{ route('users.update', $user->id) }}" id="edit"
                                                class="btn btn-warning btn-sm mr-2 my-1 edit-btn" data-toggle="modal"
                                                data-target="#editUserModal" data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-phone-number="{{ $user->phone_number }}">
                                                <i class="ri-edit-2-line"></i>
                                                Edit
                                            </a>

                                            <!-- Modal -->
                                            @include('user.edit')
                                        @endcan

                                        <!-- Update Status Petugas -->
                                        @if ($user->user_status == 'active')
                                            <form action="{{ route('users.status.update', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="inactive">
                                                <button type="submit" id="inactive"
                                                    class="btn btn-danger btn-sm mr-2 my-1">
                                                    <i class="ri-alert-line"></i> Non Aktifkan
                                                </button>
                                            </form>
                                        @elseif ($user->user_status == 'inactive')
                                            <form action="{{ route('users.status.update', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" id="active"
                                                    class="btn btn-success btn-sm mr-2 my-1">
                                                    <i class="ri-check-double-line"></i> Aktifkan
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Hapus Petugas -->
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" id="delete" class="btn btn-danger btn-sm mr-2 my-1">
                                                <i class="ri-delete-bin-line"></i> Hapus
                                            </button>
                                        </form>
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

@if (session('modal_type') === 'edit' && $errors->any())
    <script>
        $(document).ready(function() {
            $('#editUserModal').modal('show');
        });
    </script>
@endif

@section('script')
    <script>
        $(function() {
            $('#select-user-status-filter').change(e => {
                window.location.href =
                    `{{ route('users.index') }}${$(e.target).val() ? `?status=${$(e.target).val()}` : ''}`;
            });
        });

        document.querySelectorAll('#togglePassword').forEach(item => {
            item.addEventListener('click', function(e) {
                const password = e.target.closest('.input-group').querySelector('#password');
                if (password.type === 'password') {
                    password.type = 'text';
                    item.innerHTML = '<i class="fa fa-eye-slash"></i>';
                } else {
                    password.type = 'password';
                    item.innerHTML = '<i class="fa fa-eye"></i>';
                }
            });
        });

        $('.edit-btn').on('click', function(e) {
            e.preventDefault()
            $('.edit-name').val($(this).data('name'))
            $('.edit-email').val($(this).data('email'))
            $('.edit-phone-number').val($(this).data('phone-number'))
            $('#edit-modal-form').attr('action', $(this).attr('href'))
        })
    </script>
@endsection
