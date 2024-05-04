<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tarifs', function (Blueprint $table) {
            $table->double('tarif_1')->after('kategori_id');
            $table->double('tarif_2')->after('tarif_1');
            $table->double('tarif_3')->after('tarif_2');
            $table->double('tarif_4')->after('tarif_3');
            $table->double('admin')->after('tarif_4');
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
