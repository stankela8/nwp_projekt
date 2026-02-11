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
    Schema::create('goals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('game_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('type'); // 'daily_time', 'weekly_time', 'game_hours', 'rank'
        $table->integer('target_minutes')->nullable(); // za time-based ciljeve
        $table->string('target_rank')->nullable();     // za rank ciljeve
        $table->integer('current_minutes')->default(0); // opcionalno keširanje
        $table->boolean('is_completed')->default(false);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('goals');
}

};
