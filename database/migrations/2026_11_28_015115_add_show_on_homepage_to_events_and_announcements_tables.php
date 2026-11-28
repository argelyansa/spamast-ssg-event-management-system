<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('show_on_homepage')->default(false);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->boolean('show_on_homepage')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('show_on_homepage');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('show_on_homepage');
        });
    }
};