@extends('templates.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-success">
                <div class="card-header bg-success text-white text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-check-circle" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="mb-0">Pembayaran Berhasil!</h3>
                </div>

                <div class="card-body p-4">
                    {{-- Success Message --}}
                    <div class="alert alert-success" role="alert">
                        <strong>Terima kasih!</strong> Tiket Anda telah berhasil dibeli.
                    </div>

                    {{-- Transaction Details --}}
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Detail Transaksi</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted">ID Transaksi</td>
                                <td class="fw-bold">{{ $payment->transaction_id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Pembayaran</td>
                                <td class="fw-bold">{{ $payment->paid_at->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
                                    <span class="badge bg-success">{{ ucfirst($payment->status) }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- Event Details --}}
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Event</h5>
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
                                    <h6 class="fw-bold mb-2">{{ $payment->event->title }}</h6>
                                    <p class="mb-1"><strong>Tanggal:</strong> {{ $payment->event->start_date }}</p>
                                    <p class="mb-1"><strong>Lokasi:</strong> {{ $payment->event->location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ticket Summary --}}
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Ringkasan Pembelian</h5>
                        <table class="table">
                            <tr>
                                <td>Jumlah Tiket</td>
                                <td class="text-end">{{ $payment->quantity }}</td>
                            </tr>
                            <tr>
                                <td>Harga per Tiket</td>
                                <td class="text-end">Rp {{ number_format($payment->unit_price, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-active">
                                <td class="fw-bold">Total Pembayaran</td>
                                <td class="text-end fw-bold text-success">
                                    Rp {{ number_format($payment->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- Payment Method --}}
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Metode Pembayaran</h5>
                        <p class="mb-0">
                            <strong>
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
                            </strong>
                        </p>
                    </div>

                    {{-- Important Info --}}
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle"></i> Informasi Penting
                        </h6>
                        <ul class="mb-0 ps-3">
                            <li>Tiket telah dikirim ke email Anda</li>
                            <li>Silakan cek folder Spam jika tidak ada di Inbox</li>
                            <li>Simpan nomor referensi transaksi untuk keperluan di kemudian hari</li>
                            <li>Tunjukkan e-ticket saat memasuki venue</li>
                        </ul>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('payment.history') }}" class="btn btn-primary">
                            <i class="fas fa-history"></i> Lihat Riwayat Pembelian
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

            {{-- Ticket Preview --}}
            <div class="card mt-4 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-ticket-alt"></i> E-Ticket Preview</h5>
                </div>
                <div class="card-body text-center">
                    <div class="p-4 border border-2 border-primary rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="text-white">
                            <h3 class="fw-bold mb-3">{{ $payment->event->title }}</h3>
                            <div class="mb-3">
                                <p class="mb-1">Nama Pembeli: <strong>{{ Auth::user()->name }}</strong></p>
                                <p class="mb-1">Email: <strong>{{ Auth::user()->email }}</strong></p>
                                <p class="mb-3">Jumlah Tiket: <strong>{{ $payment->quantity }} x</strong></p>
                            </div>
                            <div class="border-top border-bottom border-white py-3 mb-3">
                                <p class="mb-1">Kode Tiket</p>
                                <h4 class="fw-bold" style="font-family: monospace; letter-spacing: 2px;">
                                    {{ strtoupper(str_replace('-', '', $payment->transaction_id)) }}
                                </h4>
                            </div>
                            <p class="small mb-0">Tunjukkan kode ini saat memasuki venue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
