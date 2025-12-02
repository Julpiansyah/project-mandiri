<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); // hubungkan ke event
            $table->string('type');  // jenis tiket, contoh: VIP, Regular
            $table->text('description')->nullable(); // deskripsi singkat tiket
            $table->integer('price'); // harga tiket
            $table->integer('quota')->default(0); // jumlah tiket tersedia
            $table->boolean('active')->default(true); // status tiket aktif atau tidak
            $table->timestamp('sales_start')->nullable(); // mulai penjualan tiket
            $table->timestamp('sales_end')->nullable();   // akhir penjualan tiket
            $table->timestamps();
            $table->softDeletes(); // jika mau fitur soft delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}

