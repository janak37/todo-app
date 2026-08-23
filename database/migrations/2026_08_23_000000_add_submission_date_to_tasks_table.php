<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('tasks', 'submission_date')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->date('submission_date')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tasks', 'submission_date')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('submission_date');
            });
        }
    }
};