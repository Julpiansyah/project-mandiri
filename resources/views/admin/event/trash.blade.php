@extends('templates.app')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Trash Event</h4>
            <a href="{{ route('admin.events.index') }}" class="btn btn-primary">Kembali</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Judul Event</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Lokasi</th>
                        <th>Poster</th>
                        <th class="text-center" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eventsTrash as $key => $event)
                        <tr>
                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                            <td class="align-middle">{{ $event->title }}</td>
                            <td class="align-middle">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y H:i') }}</td>
                            <td class="align-middle">
                                @if ($event->end_date)
                                    {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="align-middle">{{ $event->location }}</td>
                            <td class="align-middle">
                                @if ($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="Poster" width="80">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Restore -->
                                    <form action="{{ route('admin.events.restore', $event->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            Restore
                                        </button>
                                    </form>
                                    <!-- Hapus Permanen -->
                                    <form action="{{ route('admin.events.delete_permanent', $event->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus permanen event ini?')" class="btn btn-danger btn-sm">
                                            Hapus Permanen
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if ($eventsTrash->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data di trash.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
