@extends('templates.app')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-4">
        <h4>Daftar Tiket</h4>
        <div class="d-flex justify-content-end align-items-center mb-3 gap-3">
            <form action="{{ route('admin.tickets.update_statuses') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">Update Status Tiket</button>
            </form>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Nama Pembeli</th>
                        <th>Event</th>
                        <th>Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $key => $payment)
                        <tr>
                            <td class="text-center align-middle">{{ $payments->firstItem() + $key }}</td>
                            <td class="align-middle">{{ $payment->user->name ?? '—' }}</td>
                            <td class="align-middle">{{ $payment->event->title ?? '—' }}</td>
                            <td class="align-middle">Rp {{ number_format($payment->unit_price, 0, ',', '.') }}</td>
                            <td class="align-middle">
                                @if($payment->status_based_on_date === 'kadaluarsa')
                                    <span class="badge bg-danger">Kadaluarsa</span>
                                @elseif($payment->status_based_on_date === 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-warning">Akan Datang</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#ticketsTable').DataTable({
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
@endsection
