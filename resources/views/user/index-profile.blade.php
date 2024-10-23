@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="row mx-1 my-1">
        <div class="col-lg-12">
            <div class="card">
                <form>
                    <div class=" card-header d-flex justify-content-between">
                        <div class="header-title w-100">
                            <h4 class="card-title d-inline-block mb-0">Profil Saya</h4>
                        </div>
                        <a href="{{ route('users.edit.profile') }}" class="btn btn-primary mr-2">Edit</a>
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
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}"
                                disabled>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone_number" class="form-control"
                                value="{{ auth()->user()->phone_number }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Alamat Domisili</label>
                            <input type="text" name="complete_address" class="form-control"
                                value="{{ auth()->user()->complete_address }}" disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
