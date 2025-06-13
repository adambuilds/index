<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_relations', function (Blueprint $table) {
            $table->ulid('parent_id');
            $table->ulid('child_id');
            $table->timestamps();

            $table->primary(['parent_id', 'child_id']);
            $table->foreign('parent_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_relations');
    }
};

