@extends('templates.app')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show alert-top-right" role="alert">
            {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button> </div>
    @endif

    <div class="container mt-4 px-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Daftar Web Goers</h4>
            <div>
                <a href="{{ route('admin.users.export') }}" class="btn btn-success me-2">Ekspor Excel</a>
                <a href="{{ route('admin.users.trash') }}" class="btn btn-danger me-2">Data Sampah</a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">Tambah Data</a>
            </div>
        </div>

    </div>

    <table class="table table-bordered" id="webgoersTable">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Role</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $user)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td class="text-center">{{ $user->role }}</td>
                    <td class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin ingin menghapus data ini?')"
                                class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
