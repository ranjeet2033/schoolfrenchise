<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Make exams.exam_date nullable so exams can be perpetual without requiring yearly exam dates
        if (Schema::hasTable('exams') && Schema::hasColumn('exams', 'exam_date')) {
            try {
                DB::statement('ALTER TABLE exams MODIFY exam_date DATE NULL');
            } catch (\Throwable $e) {
                // Fallback via Schema builder if supported
            }
        }

        // 2. Add pdf_drive_link to digital_products for primary Google Drive PDF links
        if (Schema::hasTable('digital_products') && !Schema::hasColumn('digital_products', 'pdf_drive_link')) {
            Schema::table('digital_products', function (Blueprint $table) {
                $table->string('pdf_drive_link', 500)->nullable()->after('pdf_file_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('digital_products') && Schema::hasColumn('digital_products', 'pdf_drive_link')) {
            Schema::table('digital_products', function (Blueprint $table) {
                $table->dropColumn('pdf_drive_link');
            });
        }
    }
};
