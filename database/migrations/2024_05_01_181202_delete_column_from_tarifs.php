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
            $table->dropColumn('m3_umum');
            $table->dropColumn('m3_perumahan');
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
