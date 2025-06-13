<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->ulid('belongs_to_subject_id')->nullable()->after('url');
            $table->foreign('belongs_to_subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['belongs_to_subject_id']);
            $table->dropColumn('belongs_to_subject_id');
        });
    }
};
