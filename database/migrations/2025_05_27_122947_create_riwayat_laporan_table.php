<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_laporan', function (Blueprint $table) {
            $table->bigIncrements('riwayat_id');
            $table->enum('jenis', ['laporan', 'surat']);
            $table->string('judul', 200);
            $table->text('deskripsi');
            $table->enum('status', ['dalam proses', 'perlu ditinjau', 'selesai', 'ditolak']);
            $table->text('komentar')->nullable();
            $table->string('file', 100)->nullable();
            $table->string('kontak', 100)->nullable();
            $table->unsignedBigInteger('users_user_id');
            $table->unsignedBigInteger('laporan_laporan_id')->nullable();
            $table->unsignedBigInteger('surat_surat_id')->nullable();
            $table->timestamps();

            $table->foreign('users_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('laporan_laporan_id')->references('laporan_id')->on('laporan')->onDelete('cascade');
            $table->foreign('surat_surat_id')->references('surat_id')->on('surat')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_laporan');
    }
};
