<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->foreignId('centre_id')->nullable()->after('id')->constrained('centres')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->after('centre_id')->constrained('courses')->nullOnDelete();
            $table->index(['centre_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropForeign(['centre_id']);
            $table->dropForeign(['course_id']);
            $table->dropIndex(['centre_id', 'status']);
            $table->dropColumn(['centre_id', 'course_id']);
        });
    }
};
