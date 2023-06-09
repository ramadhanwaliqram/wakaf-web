@extends('layouts.admin')
@section('title', 'Data Wakaf | ' . config('app.name'))
@section('title-1', 'Data Wakaf')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    {{-- <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6> --}}
                    <div class="d-flex justify-content-end">
                        <button id="add" type="button" class="btn btn-primary"><i class="fas fa-fw fa-plus"></i>
                            Tambah Wakaf</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="feature-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Detail Wakaf</th>
                                    <th>Judul Wakaf</th>
                                    {{-- <th>Deskripsi</th> --}}
                                    <th>Target</th>
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
    @include('admin.wakaf.modal._modal')
    @include('admin.wakaf.modal._modal-image')
@endsection
{{-- addons css --}}
@push('css')
@endpush

{{-- addons js --}}
@push('js')
    <script>
        var editor;

        ClassicEditor
            .create(document.querySelector('#description'))
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });

        function formatRupiah(input) {
            // Remove non-digit characters from the value
            let value = input.value.replace(/\D/g, '');

            // Format the value as Rupiah
            let formattedValue = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);

            // Update the input field value with the formatted value
            input.value = formattedValue;
        }
    </script>
    <script>
        $(document).ready(function() {

            $('#feature-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.wakaf') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'detail',
                        name: 'detail',
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    // {
                    //     data: 'description',
                    //     name: 'description'
                    // },
                    {
                        data: 'target',
                        name: 'target'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });

            $(document).on('click', '.detail-wakaf', function() {
                let userUuid = $(this).attr('id');
                $.ajax({
                    url: '/admin/wakaf/' + userUuid,
                    dataType: 'JSON',
                    success: function(data) {
                        $('.title-wakaf').text(data.title);
                        $('.description-wakaf').html(data.description);
                        $('#image-detail-wakaf').attr('src', '/storage/wakaf/' + data
                            .thumbnail);
                        $('#modal-detail-wakaf').modal('show');
                    },
                    error: function(errors) {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                        $('#modal-detail-wakaf').modal('hide');
                    }
                });
            });

            $('#add').on('click', function() {
                $('#modal-title').html('Tambah wakaf');
                editor.setData('');
                $('#form-feature')[0].reset();
                $('#btn')
                    .removeClass('btn-info')
                    .addClass('btn-success')
                    .val('Simpan');
                $('#action').val('add');
                $('#modal-feature').modal('show');
            });

            $('#cancelModal').on('click', function() {
                $('#modal-feature').modal('hide');
                editor.setData('');
                $('#form-feature')[0].reset();
            });

            $('#form-feature').on('submit', function(event) {
                event.preventDefault();

                var url = '';
                let uuid = $('#hidden_id').val();
                let formData = new FormData($('#form-feature')[0]);

                if ($('#action').val() == 'add') {
                    url = "{{ route('admin.wakaf-add') }}";
                }

                if ($('#action').val() == 'edit') {
                    url = "/admin/wakaf/update/" + uuid;
                }

                $('#btn').prop('disabled', true);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        var html = ''
                        if (data.errors) {
                            html = data.errors[0];
                            for (var field in data.errors) {
                                var errorMessage = data.errors[field][0];
                                toastr.error(errorMessage);
                            }
                            $('#title').addClass('is-invalid');
                            $('#description').addClass('is-invalid');
                            $('#target').addClass('is-invalid');
                            $('#thumbnail').addClass('is-invalid');
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
                            $('#title').removeClass('is-invalid');
                            $('#description').removeClass('is-invalid');
                            $('#target').removeClass('is-invalid');
                            $('#thumbnail').removeClass('is-invalid');
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
                    url: '/admin/wakaf/' + userUuid,
                    dataType: 'JSON',
                    success: function(data) {
                        $('#modal-title').html('Edit wakaf')
                        $('#action').val('edit');
                        $('#title').val(data.title);
                        editor.setData(data.description);
                        $('#target').val(data.target);
                        // $('#thumbnail').html(data.description);
                        $('#thumbnail-description').text(data.thumbnail);
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
                            url: '/admin/wakaf/delete/' + user_id,
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
                                        text: 'Wakaf deleted successfully',
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
