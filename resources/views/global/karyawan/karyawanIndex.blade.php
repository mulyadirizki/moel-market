@extends('_partials.default')

@push('meta')
    <meta name="author" content="HPV">
    <meta name="keywords" content="">
    <meta name="description" content=""/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">DataTable</a></li>
                            <li class="breadcrumb-item" aria-current="page">Data Karyawan</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Data Karyawan</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- DOM/Jquery table start -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary btn-sm" onclick="addData()">Tambah Data</button>
                        <small>Data Karyawan</small>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table id="data-karyawan" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>No HP</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DOM/Jquery table end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalAddKaryawan" tabindex="-1" role="dialog" aria-labelledby="modalAddKaryawanTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Data Diri
                            </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-lg-4">
                                                <div class="form-group input-group-sm">
                                                    <label class="form-label" for="nama">Nama Lengkap :</label>
                                                    <input type="text" class="form-control" placeholder="Nama Lengkap" id="nama" nama="nama">
                                                    <small class="form-text text-muted">Please enter your full name</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group input-group-sm">
                                                    <label class="form-label" for="email">Email :</label>
                                                    <input type="email" class="form-control" placeholder="Enter email" id="email" nama="email">
                                                    <small class="form-text text-muted">Please enter your email</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group input-group-sm">
                                                    <label class="form-label" for="nohp">No HP :</label>
                                                    <input type="text" class="form-control" placeholder="Enter No HP" id="nohp" nama="nohp">
                                                    <small class="form-text text-muted">Please enter your No HP</small>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Login
                            </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-lg-4">
                                                <div class="form-group input-group-sm">
                                                    <label class="form-label" for="username">Username :</label>
                                                    <input type="text" class="form-control" placeholder="Username" id="username" nama="username">
                                                    <small class="form-text text-muted">Please enter your Username</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group input-group-sm">
                                                    <label class="form-label" for="password">password :</label>
                                                    <input type="password" class="form-control" placeholder="Password" id="password" nama="password">
                                                    <small class="form-text text-muted">Please enter your password</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group input-group-sm">
                                                    <label class="form-label" for="roles">Role :</label>
                                                    <select class="form-select" id="roles" name="roles">
                                                        <option selected disabled>Pilih Role user</option>
                                                        @foreach($role as $rl)
                                                            <option value="{{ $rl->id }}">{{ $rl->nama_role }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">Please select roles</small>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-submit" onclick="save()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            var table = $('#data-karyawan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('karyawan.data') }}",
                columns: [
                    { data: 'nama', name: 'nama' },
                    { data: 'nohp', name: 'nohp' },
                    { data: 'email', name: 'email' },
                    { data: 'mroles', name: 'mroles' },
                    { data: 'username', name: 'username' },
                     {data: 'action', name: 'action', orderable: true, searchable: true }
                ]
            });
        })

        function addData() {
            $('#modalAddKaryawan').modal('show')
        }

        function save() {
            var nama = $('#nama').val();
            var email = $('#email').val();
            var nohp = $('#nohp').val();
            var username = $('#username').val();
            var password = $('#password').val();
            var roles = $('#roles').val();
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type: "POST",
                url: "{{ route('karyawan.add') }}",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify({
                    "nama": nama,
                    "email": email,
                    "nohp": nohp,
                    "username": username,
                    "password": password,
                    "roles" : roles,
                    "_token": token
                }),
                success: function(response) {
                    if (response.success == true) {
                        $.Toast("Berhasil", response.message, "success", {
                            has_icon:true,
                            has_close_btn:true,
                            stack: true,
                            fullscreen:false,
                            timeout:8000,
                            sticky:false,
                            has_progress:true,
                            rtl:false,
                            position_class: "toast-top-right",
                            width: 150,
                        });
                        setTimeout(function() {
                            $('#modalAddKaryawan').modal('hide');
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function(err) {
                    console.log(err)
                    if(err.status == 422) {
                        $.Toast("Berhasil", err.responseJSON.message, "error", {
                            has_icon:true,
                            has_close_btn:true,
                            stack: true,
                            fullscreen:false,
                            timeout:8000,
                            sticky:false,
                            has_progress:true,
                            rtl:false,
                            position_class: "toast-top-right",
                            width: 150,
                        });
                    } else if (err.status == 400) {
                        err.responseJSON.error.map((e) => {
                            $.Toast("Berhasil", e, "error", {
                                has_icon:true,
                                has_close_btn:true,
                                stack: true,
                                fullscreen:false,
                                timeout:8000,
                                sticky:false,
                                has_progress:true,
                                rtl:false,
                                position_class: "toast-top-right",
                                width: 150,
                            });
                        })
                    }
                }
            })
        }
    </script>
@endpush