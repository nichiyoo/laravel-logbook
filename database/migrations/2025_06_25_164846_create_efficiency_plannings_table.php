<?php

use App\Models\Equipment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('efficiency_plannings', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->year('year');
      $table->integer('month');
      $table->integer('planning_week_1')->default(0);
      $table->integer('planning_week_2')->default(0);
      $table->integer('planning_week_3')->default(0);
      $table->integer('planning_week_4')->default(0);
      $table->bigInteger('target')->default(0);
      $table->foreignIdFor(Equipment::class)->constrained()->cascadeOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('efficiency_plannings');
  }
};
