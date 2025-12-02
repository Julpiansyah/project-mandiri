<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'title' => 'Pestapora 2025',
            'slug' => Str::slug('Pestapora 2025'),
            'description' => 'Pestapora adalah festival terbesar yang menampilkan berbagai artis dari berbagai genre musik. Dapatkan pengalaman luar biasa dengan atmosfer yang meriah dan hiburan kelas dunia.',
            'location' => 'Stadion Utama Gelora Bung Karno, Jakarta',
            'start_date' => '2025-06-15',
            'end_date' => '2025-06-17',
            'artis' => 'Various Artists',
            'time' => '18:00',
            'harga' => 150000,
            'image' => null,
        ]);

        Event::create([
            'title' => 'The Adams',
            'slug' => Str::slug('The Adams'),
            'description' => 'Konser eksklusif The Adams, salah satu band paling populer. Nikmati lagu-lagu hits mereka yang telah memikat jutaan penggemar.',
            'location' => 'Parkir Timur GBK, Jakarta',
            'start_date' => '2025-07-20',
            'end_date' => '2025-07-20',
            'artis' => 'The Adams',
            'time' => '19:00',
            'harga' => 200000,
            'image' => 'events/TheAdams.jpg',
        ]);

        Event::create([
            'title' => 'Hindia',
            'slug' => Str::slug('Hindia'),
            'description' => 'Hindia, pemenang berbagai penghargaan musik, menghadirkan pertunjukan spektakuler. Dengarkan koleksi lagu-lagu mereka yang ikonik.',
            'location' => 'PRJ Kemayoran, Jakarta',
            'start_date' => '2025-08-10',
            'end_date' => '2025-08-10',
            'artis' => 'Hindia',
            'time' => '20:00',
            'harga' => 175000,
            'image' => null,
        ]);

        Event::create([
            'title' => 'Jazz Night Festival',
            'slug' => Str::slug('Jazz Night Festival'),
            'description' => 'Rasakan keindahan musik jazz dalam acara malam yang elegan. Featuring musisi-musisi jazz internasional terkemuka.',
            'location' => 'Kafe Besar, Senayan',
            'start_date' => '2025-09-05',
            'end_date' => '2025-09-05',
            'artis' => 'International Jazz Artists',
            'time' => '19:30',
            'harga' => 250000,
            'image' => null,
        ]);

        Event::create([
            'title' => 'Indonesia Music Fest 2025',
            'slug' => Str::slug('Indonesia Music Fest 2025'),
            'description' => 'Festival musik terbesar di Indonesia menampilkan artis lokal dan internasional terbaik. Pengalaman musik yang tak terlupakan selama tiga hari.',
            'location' => 'Bumi Serpong Damai, Tangerang',
            'start_date' => '2025-10-17',
            'end_date' => '2025-10-19',
            'artis' => 'Multiple Artists',
            'time' => '17:00',
            'harga' => 300000,
            'image' => null,
        ]);
    }
}
