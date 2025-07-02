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
            $table->string('nome')->comment('Nome da tarefa');
            $table->text('descricao')->nullable()->comment('Descrição detalhada da tarefa');
            $table->boolean('finalizado')->default(false)->comment('Status de conclusão da tarefa');
            $table->datetime('data_limite')->nullable()->comment('Data limite para conclusão');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index('finalizado');
            $table->index('data_limite');
            $table->index(['finalizado', 'data_limite']);
            $table->index('deleted_at');
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
