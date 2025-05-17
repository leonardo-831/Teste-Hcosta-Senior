<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->default('#ccc');
        });

        DB::table('status')->insert([
            ['name' => 'A Fazer', 'color' => '#ff0000'],
            ['name' => 'Em Progresso', 'color' => '#ffff00'],
            ['name' => 'Concluído', 'color' => '#00ff00']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
