<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up()
    {
        // First, create a temporary column
        Schema::table('tamu', function (Blueprint $table) {
            $table->dateTime('new_check_out')->nullable()->after('check_out');
        });

        // Convert existing time-only values to full datetime
        $tz = 'Asia/Makassar';
        DB::table('tamu')->whereNotNull('check_out')->orderBy('id')->chunk(100, function ($tamus) use ($tz) {
            foreach ($tamus as $tamu) {
                if (preg_match('/^\d{2}:\d{2}$/', $tamu->check_out)) {
                    // If check_out is HH:MM format, combine with created_at date
                    $created = $tamu->created_at ? Carbon::parse($tamu->created_at) : Carbon::now();
                    $checkOut = $created->copy()->setTimeFromTimeString($tamu->check_out);
                } else {
                    // Try parsing as is, fallback to created_at
                    try {
                        $checkOut = Carbon::parse($tamu->check_out);
                    } catch (\Exception $e) {
                        $checkOut = $tamu->created_at ? Carbon::parse($tamu->created_at) : null;
                    }
                }
                
                DB::table('tamu')
                    ->where('id', $tamu->id)
                    ->update(['new_check_out' => $checkOut]);
            }
        });

        // Drop old column and rename new one
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropColumn('check_out');
        });

        Schema::table('tamu', function (Blueprint $table) {
            $table->renameColumn('new_check_out', 'check_out');
        });
    }

    public function down()
    {
        // For rollback, convert datetime back to time-only string
        Schema::table('tamu', function (Blueprint $table) {
            $table->string('old_check_out')->nullable()->after('check_out');
        });

        DB::table('tamu')->whereNotNull('check_out')->orderBy('id')->chunk(100, function ($tamus) {
            foreach ($tamus as $tamu) {
                $time = $tamu->check_out ? Carbon::parse($tamu->check_out)->format('H:i') : null;
                DB::table('tamu')
                    ->where('id', $tamu->id)
                    ->update(['old_check_out' => $time]);
            }
        });

        Schema::table('tamu', function (Blueprint $table) {
            $table->dropColumn('check_out');
        });

        Schema::table('tamu', function (Blueprint $table) {
            $table->renameColumn('old_check_out', 'check_out');
        });
    }
};