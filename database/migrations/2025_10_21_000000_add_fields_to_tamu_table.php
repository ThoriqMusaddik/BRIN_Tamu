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
        Schema::table('tamu', function (Blueprint $table) {
            $table->integer('jumlah_orang')->default(1)->after('check_out');
            $table->string('kontak')->nullable()->after('jumlah_orang');
            $table->text('keterangan')->nullable()->after('kontak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropColumn(['jumlah_orang','kontak','keterangan']);
        });
    }
};
