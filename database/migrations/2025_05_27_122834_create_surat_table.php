<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->bigIncrements('surat_id');
            $table->enum('jenis_surat', ['keterangan', 'pengantar']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat');
    }
};

