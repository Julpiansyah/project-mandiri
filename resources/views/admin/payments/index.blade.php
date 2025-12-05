@extends('templates.app')

@section('content')
    <div class="container mt-4">
        <h4>Riwayat Transaksi Pengguna</h4>
        <div class="mb-3 d-flex justify-content-end gap-2">
            <a href="{{ route('admin.payments.export') }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        @if($payments->count() > 0)
            <div class="table-responsive">
                <table id="paymentsTable" class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>ID Transaksi</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $key => $payment)
                            <tr>
                                <td class="align-middle">{{ ($payments->currentPage() - 1) * $payments->perPage() + $key + 1 }}</td>
                                <td class="align-middle"><small class="font-monospace">{{ $payment->transaction_id }}</small></td>
                                <td class="align-middle">{{ $payment->user->name ?? '—' }}<br><small>{{ $payment->user->email ?? '' }}</small></td>
                                <td class="align-middle">{{ $payment->event->title ?? '—' }}</td>
                                <td class="align-middle">{{ $payment->quantity }} x</td>
                                <td class="align-middle">Rp {{ number_format($payment->total_price,0,',','.') }}</td>
                                <td class="align-middle">{{ ucfirst(str_replace('_',' ', $payment->payment_method ?? '-')) }}</td>
                                <td class="align-middle">
                                    <span class="badge bg-success">Berhasil</span>
                                </td>
                                <td class="align-middle">{{ $payment->created_at->format('d M Y H:i') }}</td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-sm btn-info">Detail</a>
                                    <a href="{{ route('payment.downloadPDF', $payment->id) }}" class="btn btn-sm btn-primary">PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $payments->links() }}
                </div>
            </div>
        @else
            <div class="alert alert-info">Belum ada transaksi.</div>
        @endif
    </div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#paymentsTable').DataTable({
            "paging": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "lengthChange": false,
            "language": {
                "search": "Cari:",
                "zeroRecords": "Tidak ada data yang ditemukan"
            }
        });
    });
</script>
@endpush
