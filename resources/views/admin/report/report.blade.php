@extends('layouts.admin')
@section('title', 'Data Laporan | ' . config('app.name'))
@section('title-1', 'Data Laporan')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    {{-- <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6> --}}
                    <form id="form-feature" enctype="multipart/form-data" method="GET" action="report/print">
                        <div class="d-flex justify-content-end">
                            {{-- <button id="add" type="submit" class="btn btn-primary"><i class="fas fa-fw fa-print"></i>
                                Cetak</button> --}}
                            <a href="report/print" target="_blank" class="btn btn-primary"><i class="fas fa-fw fa-print"></i>
                                Cetak</a>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="feature-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Program</th>
                                    <th>Target</th>
                                    <th>Terkumpul</th>
                                    <th>Sisa Target</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($report == '[]')
                                    <tr>
                                        <td class="ps-4">
                                            <p>Tidak ada data!</p>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($report as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <p>{{ $loop->iteration }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $item->title }}</p>
                                            </td>
                                            <td>
                                                <p>Rp {{ number_format($item->target, 0, ',', '.') }}</p>
                                            </td>
                                            <td>
                                                <p>Rp {{ number_format($item->transaction->sum('amount'), 0, ',', '.') }}
                                                </p>
                                            </td>
                                            <td>
                                                <p>Rp
                                                    {{ number_format($item->target - $item->transaction->sum('amount'), 0, ',', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- addons css --}}
@push('css')
@endpush
