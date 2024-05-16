<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->required();
            $table->string('board_id', 128)->required();
            $table->string('tasks')->nullable()
                ->comment('{"subtype": "csv_string"}');

            $table->foreign('board_id')->references('name')->on('boards')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();

            $table->index(['name', 'board_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('columns');
    }
};
