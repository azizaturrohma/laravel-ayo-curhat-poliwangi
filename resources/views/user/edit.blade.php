<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Data Petugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="edit-modal-form">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" class="form-control edit-name" value="{{ old('name') }}">
                        <span class="text-danger"></span>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control edit-email"
                            value="{{ old('email') }}">
                        <span class="text-danger"></span>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Nomor Telepon</label>
                        <input type="text" name="phone_number" class="form-control edit-phone-number"
                            value="{{ old('phone_number') }}">
                        <span class="text-danger"></span>
                        @error('phone_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="password">
                            <div class="input-group-append">
                                <span class="input-group-text" id="togglePassword"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>
                        <span class="text-danger"></span>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="user_id" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#edit-modal-form').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').text('');
            let isValid = true;

            function setError(inputName, message) {
                let inputField = $(`input[name="${inputName}"]`);
                inputField.closest('.form-group').find('.text-danger').text(message);
            }

            let name = $('.edit-name').val();
            if (!name.match(/^[a-zA-Z\s]+$/)) {
                setError('name', 'Nama tidak boleh mengandung karakter atau simbol.');
                isValid = false;
            } else if (name.length < 5 || name.length > 40) {
                setError('name', 'Nama harus terdiri dari 5 sampai 40 karakter.');
                isValid = false;
            }

            let email = $('.edit-email').val();
            if (!email.match(/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/)) {
                setError('email', 'Format email tidak valid.');
                isValid = false;
            }

            let phoneNumber = $('.edit-phone-number').val();
            if (!phoneNumber.match(/^[0-9]+$/) || phoneNumber.length < 11 || phoneNumber.length > 13) {
                setError('phone_number',
                    'Nomor telepon hanya boleh angka dengan panjang 11-13 karakter.');
                isValid = false;
            }

            let password = $('#password').val();
            if (password.length > 0 && password.length < 6) {
                setError('password', 'Password minimal 6 karakter.');
                isValid = false;
            }

            if (isValid) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Ingin memperbarui data ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            }
        });

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

    @if ($errors->has('update_error'))
        <
        div class = "alert alert-danger" >
        {{ $errors->first('update_error') }}
            <
            /div>
    @endif
</script>
