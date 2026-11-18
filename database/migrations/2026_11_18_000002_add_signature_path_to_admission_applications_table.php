<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admission_applications') && !Schema::hasColumn('admission_applications', 'signature_path')) {
            Schema::table('admission_applications', function (Blueprint $table) {
                $table->string('signature_path')->nullable()->after('photo_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('admission_applications') && Schema::hasColumn('admission_applications', 'signature_path')) {
            Schema::table('admission_applications', function (Blueprint $table) {
                $table->dropColumn('signature_path');
            });
        }
    }
};
