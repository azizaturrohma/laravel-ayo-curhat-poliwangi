@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="row mx-1 my-1">
        <div class="col-lg-12">
            <div class="card">
                <form id="edit-profile-form" action="{{ route('users.update.profile') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class=" card-header d-flex justify-content-between">
                        <div class="header-title w-100">
                            <h4 class="card-title d-inline-block mb-0">Edit Profil Saya</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}"
                                disabled>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <input type="text" name="gender" class="form-control"
                                value="{{ ucfirst(auth()->user()->gender) }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone_number" class="form-control"
                                value="{{ auth()->user()->phone_number }}">
                            @error('phone_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Alamat Domisili</label>
                            <input type="text" name="complete_address" class="form-control"
                                value="{{ auth()->user()->complete_address }}">
                            @error('complete_address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#edit-profile-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log("Submit ditangkap!");
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Ingin memperbarui data anda?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        } else {
            console.error("#edit-profile-form tidak ditemukan");
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

{{-- @if (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            });
        });
    </script> --}}
