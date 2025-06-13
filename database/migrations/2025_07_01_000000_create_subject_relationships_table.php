<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_relationships', function (Blueprint $table) {
            $table->ulid('subject_id');
            $table->ulid('related_subject_id');
            $table->string('type')->default('belongs_to');
            $table->timestamps();

            $table->primary(['subject_id', 'related_subject_id', 'type'], 'subject_relationships_primary');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('related_subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['belongs_to_subject_id']);
            $table->dropColumn('belongs_to_subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->ulid('belongs_to_subject_id')->nullable();
            $table->foreign('belongs_to_subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

        Schema::dropIfExists('subject_relationships');
    }
};
