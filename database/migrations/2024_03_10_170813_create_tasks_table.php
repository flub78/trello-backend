<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->required();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('column_id')->required();
            $table->date('due_date')->nullable();
            $table->boolean('completed')->default(false)->comment('{"subtype": "boolean"}');
            $table->string('image')->nullable();
            $table->string('href')->nullable();
            $table->boolean('favorite')->default(false)->comment('{"subtype": "boolean"}');
            $table->boolean('watched')->default(false)->comment('{"subtype": "boolean"}');
            $table->timestamps();

            $table->foreign('column_id')->references('id')->on('columns')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
