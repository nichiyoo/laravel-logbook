<?php

use App\Models\User;
use App\Models\Shift;
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
    Schema::create('daily_reports', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->date('date');
      $table->string('report')->nullable();
      $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
      $table->foreignIdFor(Shift::class)->constrained()->cascadeOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('daily_reports');
  }
};
