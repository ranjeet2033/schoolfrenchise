<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_products', function (Blueprint $table) {
            if (!Schema::hasColumn('digital_products', 'demo_url')) {
                $table->string('demo_url', 500)->nullable()->after('sample_pdf_path');
            }
            if (!Schema::hasColumn('digital_products', 'intro_video_url')) {
                $table->string('intro_video_url', 500)->nullable()->after('youtube_video_url');
            }
            if (!Schema::hasColumn('digital_products', 'resource_links')) {
                $table->json('resource_links')->nullable()->after('pdf_file_path');
            }
            if (!Schema::hasColumn('digital_products', 'video_links')) {
                $table->json('video_links')->nullable()->after('duration');
            }
        });
    }

    public function down(): void
    {
        Schema::table('digital_products', function (Blueprint $table) {
            $cols = [];
            foreach (['demo_url', 'intro_video_url', 'resource_links', 'video_links'] as $col) {
                if (Schema::hasColumn('digital_products', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
