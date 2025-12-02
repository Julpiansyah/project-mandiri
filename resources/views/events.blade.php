@extends('templates.app')

@section('content')
<div class="container mt-5">
    {{-- Header --}}
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('success') }}</strong>
            @if(Auth::check())
                Selamat Datang! <b>{{ Auth::user()->name }}</b>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (Session::get('logout'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ Session::get('logout') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Title Section --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold mb-3">🎵 Semua Konser & Event Musik</h1>
        <p class="text-muted">Temukan dan pesan tiket untuk event musik favorit Anda</p>
    </div>

    {{-- Events Grid --}}
    @if($events->count() > 0)
        <div class="row g-4 mb-5">
            @foreach($events as $event)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm hover-card" style="transition: all 0.3s ease;">
                    {{-- Event Image --}}
                    <div class="position-relative overflow-hidden" style="height: 280px;">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}"
                                 class="card-img-top w-100 h-100"
                                 style="object-fit: cover;"
                                 alt="{{ $event->title }}">
                        @else
                            <img src="https://via.placeholder.com/400x280?text={{ urlencode($event->title) }}"
                                 class="card-img-top w-100 h-100"
                                 style="object-fit: cover;"
                                 alt="{{ $event->title }}">
                        @endif

                        {{-- Badge Status --}}
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($event->start_date > now())
                                <span class="badge bg-success">Akan Datang</span>
                            @elseif($event->end_date >= now())
                                <span class="badge bg-danger">Sedang Berlangsung</span>
                            @else
                                <span class="badge bg-secondary">Selesai</span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body d-flex flex-column">
                        {{-- Event Title --}}
                        <h5 class="card-title fw-bold mb-2">{{ $event->title }}</h5>

                        {{-- Artist --}}
                        @if($event->artis)
                            <p class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> <strong>{{ $event->artis }}</strong>
                                </small>
                            </p>
                        @endif

                        {{-- Location --}}
                        <p class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt"></i> {{ Str::limit($event->location, 50) }}
                            </small>
                        </p>

                        {{-- Date & Time --}}
                        <p class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> {{ $event->start_date }}
                                @if($event->time)
                                    <i class="fas fa-clock ms-2"></i> {{ $event->time }}
                                @endif
                            </small>
                        </p>

                        {{-- Description --}}
                        <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                            {{ Str::limit($event->description, 80) }}
                        </p>

                        {{-- Price --}}
                        <div class="mb-3">
                            <span class="badge bg-warning text-dark fw-bold">
                                <i class="fas fa-ticket-alt"></i>
                                Rp {{ number_format($event->harga ?? 100000, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Button --}}
                        <div class="mt-auto">
                            <a href="{{ route('events.detail', $event->id) }}"
                               class="btn btn-primary w-100 fw-bold">
                                <i class="fas fa-shopping-cart"></i> Pesan Tiket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Total Events Info --}}
        <div class="alert alert-info text-center mt-5">
            <strong>Total {{ $events->count() }} Event</strong> tersedia untuk dipesan
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-5">
            <div style="font-size: 4rem; opacity: 0.3;">📭</div>
            <h4 class="mt-3">Belum ada event</h4>
            <p class="text-muted">Mohon ditunggu, event menarik akan segera hadir!</p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary mt-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    @endif
</div>

<style>
    .hover-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .hover-card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-5px);
    }

    .card-img-top {
        transition: transform 0.3s ease;
    }

    .hover-card:hover .card-img-top {
        transform: scale(1.05);
    }
</style>
@endsection

