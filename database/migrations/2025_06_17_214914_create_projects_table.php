<?php

use App\Enums\StatusType;
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
    $status = StatusType::cases();

    Schema::create('projects', function (Blueprint $table) use ($status) {
      $table->id();
      $table->timestamps();
      $table->string('name');
      $table->text('description');
      $table->enum('status', array_map(fn($status) => $status->value, $status))->default(StatusType::DRAFT);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('projects');
  }
};
