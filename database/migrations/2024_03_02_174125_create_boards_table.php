<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('boards', function (Blueprint $table) {
            $table->string('name', 128)->primary();
            $table->string('description')->nullable();
            $table->string('email', 128)->unique();
            $table->boolean('favorite')->default(false)
                ->comment('{"subtype": "boolean"}');
            $table->timestamp('read_at')->nullable()
                ->comment('{"fillable": "false"}');
            $table->string('href')->nullable();
            $table->string('picture')->nullable();
            $table->enum('theme', ['light', 'dark'])->default('light')->nullable();
            $table->string('lists')->nullable()
                ->comment('{"subtype": "csv_string"}');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('boards');
    }
};
