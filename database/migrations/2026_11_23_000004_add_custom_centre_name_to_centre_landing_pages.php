<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('centre_landing_pages', function (Blueprint $table) {
            $table->string('custom_centre_name', 191)->nullable()->after('slug');
            $table->string('header_subtext', 191)->nullable()->after('custom_centre_name');
        });
    }

    public function down(): void
    {
        Schema::table('centre_landing_pages', function (Blueprint $table) {
            $table->dropColumn(['custom_centre_name', 'header_subtext']);
        });
    }
};
