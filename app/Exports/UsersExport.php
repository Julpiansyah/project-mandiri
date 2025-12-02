<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class UsersExport implements FromCollection, WithHeadings
{
    // Ambil semua data users
    public function collection()
    {
        return User::select('id', 'name', 'email', 'created_at', 'updated_at')->get();
    }

    // Header kolom
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Dibuat Pada',
            'Diubah Pada',
        ];
    }
}
