<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventsExport implements FromCollection, WithHeadings
{
    // Ambil semua data dari tabel events
    public function collection()
    {
        return Event::select('id', 'title', 'location', 'start_date', 'end_date')->get();
    }

    // Tambahkan header kolom
    public function headings(): array
    {
        return [
            'ID',
            'Judul Event',
            'Lokasi',
            'Tanggal Mulai',
            'Tanggal Selesai',
        ];
    }
}
