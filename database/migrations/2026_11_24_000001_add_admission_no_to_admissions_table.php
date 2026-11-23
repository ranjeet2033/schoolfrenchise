<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admissions') && !Schema::hasColumn('admissions', 'admission_no')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->string('admission_no', 100)->nullable()->after('course_id')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('admissions') && Schema::hasColumn('admissions', 'admission_no')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->dropColumn('admission_no');
            });
        }
    }
};
