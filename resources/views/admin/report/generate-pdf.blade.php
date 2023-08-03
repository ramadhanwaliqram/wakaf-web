<title>Cetak Laporan Program Wakaf</title>
<center>
    <h1>Masjid Fathur Rahman</h1>
    <h3>Laporan Program Wakaf</h3>
</center>
<div class="table-responsive">
    <table border="1" class="table table-bordered" id="feature-table" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
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
                    <tr style="text-align: center">
                        <td>
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
