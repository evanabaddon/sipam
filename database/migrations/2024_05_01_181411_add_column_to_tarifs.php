<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tarifs', function (Blueprint $table) {
            $table->foreignId('kategori_id')->after('id');
            $table->double('tarif_bulanan')->after('kategori_id');
            $table->double('tarif_pemasangan')->after('tarif_bulanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarifs', function (Blueprint $table) {
            //
        });
    }
};
