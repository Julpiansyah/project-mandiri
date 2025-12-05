@extends('templates.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4 fw-bold">
                <i class="fas fa-history"></i> Riwayat Pembelian Tiket
            </h2>

            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover shadow-sm">
                        <thead class="table-primary">
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Event</th>
                                <th>Jumlah Tiket</th>
                                <th>Total Pembayaran</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <small class="font-monospace">{{ $payment->transaction_id }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $payment->event->title }}</strong>
                                    </td>
                                    <td>{{ $payment->quantity }} x</td>
                                    <td class="text-success fw-bold">
                                        Rp {{ number_format($payment->total_price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @switch($payment->payment_method)
                                            @case('credit_card')
                                                <span class="badge bg-info">Kartu Kredit</span>
                                                @break
                                            @case('bank_transfer')
                                                <span class="badge bg-warning">Transfer Bank</span>
                                                @break
                                            @case('e_wallet')
                                                <span class="badge bg-success">E-Wallet</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Berhasil</span>
                                    </td>
                                    <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('payment.downloadPDF', $payment->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> PDF
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                    <h5 class="mt-3">Belum ada pembelian tiket</h5>
                    <p class="mb-2">Anda belum memiliki riwayat pembelian tiket.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-ticket-alt"></i> Belanja Tiket Sekarang
                    </a>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#userPaymentsTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(difilter dari _MAX_ total entri)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush
