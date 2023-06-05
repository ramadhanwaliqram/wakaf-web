@extends('layouts.admin')
@section('title', 'Data Wakif | ' . config('app.name'))
@section('title-1', 'Data Wakif')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    {{-- <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6> --}}
                    <div class="d-flex justify-content-end">
                        <button id="add" type="button" class="btn btn-primary"><i class="fas fa-fw fa-plus"></i>
                            Tambah Wakif</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="feature-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>No Handphone</th>
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
    </div>

    {{-- modal --}}
    @include('admin.wakif.modal._modal')
@endsection
{{-- addons css --}}
@push('css')
@endpush

{{-- addons js --}}
@push('js')
    <script>
        $(document).ready(function() {
            $('#feature-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.wakif') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

            $('#add').on('click', function() {
                $('#modal-title').html('Tambah Wakif');
                $('#btn')
                    .removeClass('btn-info')
                    .addClass('btn-success')
                    .val('Simpan');
                $('#action').val('add');
                $('#modal-feature').modal('show');
            });

            $('#cancelModal').on('click', function() {
                $('#modal-feature').modal('hide');
                $('#form-feature')[0].reset();

            });

            $('#form-feature').on('submit', function(event) {
                event.preventDefault();

                var url = '';
                let uuid = $('#hidden_id').val();
                if ($('#action').val() == 'add') {
                    url = "{{ route('admin.wakif-add') }}";
                }

                if ($('#action').val() == 'edit') {
                    url = "/admin/wakif/update/" + uuid;
                }

                $('#btn').prop('disabled', true);

                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'JSON',
                    data: $(this).serialize(),
                    success: function(data) {
                        var html = ''
                        if (data.errors) {
                            html = data.errors[0];
                            $('#name').addClass('is-invalid');
                            $('#email').addClass('is-invalid');
                            $('#address').addClass('is-invalid');
                            $('#phone').addClass('is-invalid');
                            Swal.fire('Error!', html, 'error');
                            $('#btn').prop('disabled', false);
                        }

                        if (data.success) {
                            if ($('#action').val() == 'add') {
                                Swal.fire('Sukses!', 'Data berhasil ditambah!', 'success');
                            }

                            if ($('#action').val() == 'edit') {
                                Swal.fire('Sukses!', 'Data berhasil diupdate!', 'success');
                            }

                            $('#modal-feature').modal('hide');
                            $('#name').removeClass('is-invalid');
                            $('#email').removeClass('is-invalid');
                            $('#address').removeClass('is-invalid');
                            $('#phone').removeClass('is-invalid');
                            $('#form-feature')[0].reset();
                            $('#action').val('add');
                            $('#btn').prop('disabled', false);
                            $('#btn').val('Simpan');
                            $('#feature-table').DataTable().ajax.reload();
                        }
                    },
                    error: function(errors) {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                        $('#btn').prop('disabled', false);
                    }
                });
            });


            // //HANDLE MODAL EDIT
            $(document).on('click', '.edit', function() {
                let userUuid = $(this).attr('id');
                $.ajax({
                    url: '/admin/wakif/' + userUuid,
                    dataType: 'JSON',
                    success: function(data) {
                        $('#modal-title').html('Edit Wakif')
                        $('#action').val('edit');
                        $('#name').val(data.name);
                        $('#email').val(data.email);
                        $('#address').val(data.address);
                        $('#phone').val(data.phone);
                        $('#hidden_id').val(data.uuid);
                        $('#btn')
                            .removeClass('btn-success')
                            .addClass('btn-info')
                            .val('Update');
                        $('#modal-feature').modal('show');
                    },
                    error: function(errors) {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                        $('#modal-feature').modal('hide');
                    }
                });
            });

            // //HANDLE DELETE DATA
            $(document).on('click', '.delete', function() {
                let user_id = $(this).attr('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: "DELETE",
                            url: '/admin/wakif/delete/' + user_id,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Please Wait..!',
                                    text: 'Is working..',
                                    icon: 'info',
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                })
                            },
                            success: function(data) {
                                setTimeout(function() {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'User  deleted successfully',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                    $('#feature-table').DataTable().ajax
                                        .reload();
                                }, 500);
                            },
                            error: function(errors) {
                                Swal.fire('Error!', 'Something went wrong!', 'error');
                                Swal.hideLoading();
                            }
                        });
                    }
                })
            });

        });
    </script>
@endpush
