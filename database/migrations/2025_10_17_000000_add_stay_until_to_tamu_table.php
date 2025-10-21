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
            if (! Schema::hasColumn('tamu', 'stay_until')) {
                $table->date('stay_until')->nullable()->after('check_out');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            if (Schema::hasColumn('tamu', 'stay_until')) {
                $table->dropColumn('stay_until');
            }
        });
    }
};
