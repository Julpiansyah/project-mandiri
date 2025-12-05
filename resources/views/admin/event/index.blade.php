@extends('templates.app')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-4">
        <h4>Daftar Event</h4>
        <div class="d-flex justify-content-end align-items-center mb-3 gap-3">
            <a href="{{ route('admin.events.export') }}" class="btn btn-success">Ekspor Excel</a>
            <a href="{{ route('admin.events.trash') }}" class="btn btn-danger">Data Sampah</a>
            <a href="{{ route('admin.events.create') }}" class="btn btn-success">Tambah Event</a>
        </div>

        <div class="table-responsive">
            <table id="eventsTable" class="table table-striped table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Judul Event</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Lokasi</th>
                        <th>Poster</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td class="text-center align-middle">{{ $event->id }}</td>
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
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.events.delete', $event->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ingin menghapus event ini?')"
                                            class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        console.log('Initializing DataTable for eventsTable');
        if ($.fn.DataTable) {
            console.log('DataTable plugin is available');
            $('#eventsTable').DataTable({
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
            console.log('DataTable initialized successfully');
        } else {
            console.error('DataTable plugin is not available');
        }
    });
</script>
@endpush
