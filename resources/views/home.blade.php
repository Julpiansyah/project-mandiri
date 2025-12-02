@extends('templates.app')

@section('content')
<div class="">
    @if (Session::get('success'))
        {{-- Auth::user() : mengambil data user yang login --}}
        {{-- format : Auth::user()->column_di_fillable --}}
        <div class="alert alert-success w-100">
            {{ Session::get('success') }} <b>Selamat Datang! {{ Auth::user()->name }}</b>
        </div>
    @endif
    @if (Session::get('logout'))
        <div class="alert alert-warning w-100">
            {{ Session::get('logout') }}
        </div>
    @endif

    <div id="carouselExampleIndicators" class="carousel slide" data-mdb-ride="carousel" data-mdb-carousel-init>
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://froyonion.sgp1.cdn.digitaloceanspaces.com/images/blogdetail/42b93a0112cf942bb1cc9ce65a66034a00a95103.jpg"
                class="d-block w-100" style="height: 650px" alt="Wild Landscape" />
            </div>
            <div class="carousel-item">
                <img src="https://pestapora.com/_nuxt/hero-pestapora.BYR-FmES.jpg"
                    class="d-block w-100"style="height: 650px" alt="Exotic Fruits" />
            </div>
            <div class="carousel-item">
                <img src="https://assets.promediateknologi.id/crop/0x0:0x0/750x500/webp/photo/2023/07/19/HINDIA-2231495827.jpg"
                    class="d-block w-100"style="height: 650px" alt="Camera" />
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselExampleIndicators"
            data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselExampleIndicators"
            data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="container">
        <h2 class="text-center text-white mb-4 fw-bold">Konser Populer</h2>

        <div class="row g-4">
            @foreach($events as $event)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://via.placeholder.com/380x380?text=No+Image' }}"
                        class="card-img-top img-fluid"
                        style="height: 380px; object-fit: cover;"
                        alt="{{ $event->title }}">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                        <a href="{{ route('events.detail', $event->id) }}" class="btn btn-warning">Pesan Tiket</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
