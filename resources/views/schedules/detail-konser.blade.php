@extends('templates.app')

@section('content')
<style>
    .konser-header {
        background: linear-gradient(135deg, #4e54c8, #8f94fb);
        border-radius: 20px;
        padding: 35px;
        color: white;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .info-box {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(12px);
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .btn-pesan {
        font-size: 18px;
        padding: 12px 25px;
        border-radius: 15px;
        font-weight: bold;
    }

    .label {
        font-weight: 600;
        color: #555;
    }

    .value {
        font-weight: 700;
        color: #222;
    }
</style>

<div class="container mt-5">

    {{-- Header Konser --}}
    <div class="konser-header mb-4">
        <h2 class="fw-bold mb-1">{{ $event->title }}</h2>
        <p class="mb-0">{{ $event->artis }}</p>
    </div>

    {{-- Card Detail --}}
    <div class="info-box">

        <h4 class="fw-bold mb-3">Informasi Konser</h4>

        <div class="row mb-2">
            <div class="col-md-6">
                <p><span class="label">Lokasi:</span> <span class="value">{{ $event->location }}</span></p>
                <p><span class="label">Tanggal:</span> <span class="value">{{ $event->start_date }}</span></p>
            </div>

            <div class="col-md-6">
                <p><span class="label">Waktu:</span> <span class="value">{{ $event->time }}</span></p>
                <p><span class="label">Harga Tiket:</span>
                    <span class="value text-success">Rp {{ number_format($event->harga, 0, ',', '.') }}</span>
                </p>
            </div>
        </div>

        <hr>

        {{-- Tombol Pesan --}}
        <div class="text-end">
            @if(Auth::check())
                <a href="{{ route('payment.create', $event->id) }}"
                   class="btn btn-primary btn-pesan shadow-lg">
                    <i class="fas fa-shopping-cart"></i> Pesan Tiket Sekarang
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="btn btn-primary btn-pesan shadow-lg">
                    <i class="fas fa-sign-in-alt"></i> Login untuk Memesan Tiket
                </a>
            @endif
        </div>

    </div>

</div>
@endsection
