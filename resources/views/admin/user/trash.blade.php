@extends('templates.app')

@section('content')
<div class="container mt-5">
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ Session::get('success') }}</div>
    @endif
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <h5>Data Sampah Pengguna</h5>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Email</th>
                <th class="text-center">Role</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $user)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">{{ $user->role }}</td>
                    <td class="text-center d-flex justify-content-center gap-2">
                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">Kembalikan</button>
                        </form>
                        <form action="{{ route('admin.users.delete_permanent', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @if ($users->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data sampah</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
