@extends('templates.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt"></i> Detail Transaksi
                    </h4>
                </div>
                <div class="card-body p-4">

                    {{-- Transaction Header --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted">ID Transaksi</p>
                                <h5 class="fw-bold font-monospace">{{ $payment->transaction_id }}</h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="text-muted">Status Pembayaran</p>
                                <h5>
                                    @if($payment->status === 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>

                    {{-- Pembeli Info --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase text-muted mb-3">Informasi Pembeli</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 30%;">Nama</td>
                                <td class="fw-bold">{{ $payment->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td class="fw-bold">{{ $payment->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Telepon</td>
                                <td class="fw-bold">{{ $payment->user->phone ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Event Info --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase text-muted mb-3">Event</h6>
                        <div class="p-3 bg-light rounded">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    @if($payment->event->image)
                                        <img src="{{ asset('storage/' . $payment->event->image) }}"
                                             alt="{{ $payment->event->title }}"
                                             class="img-fluid rounded" style="height: 120px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/120" alt="No Image" class="img-fluid rounded">
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h5 class="fw-bold mb-2">{{ $payment->event->title }}</h5>
                                    <p class="mb-1"><i class="fas fa-calendar"></i> <strong>Tanggal:</strong> {{ $payment->event->start_date }}</p>
                                    <p class="mb-1"><i class="fas fa-map-marker-alt"></i> <strong>Lokasi:</strong> {{ $payment->event->location }}</p>
                                    <p class="mb-0"><i class="fas fa-info-circle"></i> <strong>Deskripsi:</strong> {{ Str::limit($payment->event->description, 100) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ticket Details --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase text-muted mb-3">Detail Tiket</h6>
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tiket {{ $payment->event->title }}</td>
                                    <td class="text-end">{{ $payment->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($payment->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($payment->total_price, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="3" class="text-end fw-bold">TOTAL</td>
                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($payment->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Payment Info --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase text-muted mb-3">Informasi Pembayaran</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 30%;">Metode Pembayaran</td>
                                <td class="fw-bold">
                                    @switch($payment->payment_method)
                                        @case('credit_card')
                                            <i class="fas fa-credit-card"></i> Kartu Kredit
                                            @break
                                        @case('bank_transfer')
                                            <i class="fas fa-university"></i> Transfer Bank
                                            @break
                                        @case('e_wallet')
                                            <i class="fas fa-wallet"></i> E-Wallet
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Pembayaran</td>
                                <td class="fw-bold">{{ $payment->paid_at ? $payment->paid_at->format('d F Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Catatan</td>
                                <td class="fw-bold">{{ $payment->notes ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Important Notes --}}
                    <div class="alert alert-warning mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle"></i> Penting
                        </h6>
                        <ul class="mb-0 ps-3">
                            <li>Tiket telah dikirim ke email Anda</li>
                            <li>Simpan e-ticket dengan baik</li>
                            <li>Tunjukkan e-ticket saat memasuki venue</li>
                            <li>Tiket tidak dapat dikembalikan atau ditukar</li>
                        </ul>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-grid gap-2">
                        <button onclick="window.print()" class="btn btn-info">
                            <i class="fas fa-print"></i> Cetak Transaksi
                        </button>
                        <a href="{{ route('payment.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, a[href] { display: none; }
        body { background: white; }
        .card { box-shadow: none; border: 1px solid #ddd; }
    }
</style>
@endsection
