<?php

use App\Models\Shift;
use App\Models\Project;
use App\Models\Equipment;
use App\Enums\EquipmentType;
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
    $types = EquipmentType::cases();

    Schema::create('logbooks', function (Blueprint $table) use ($types) {
      $table->id();
      $table->timestamps();
      $table->date('date');
      $table->string('operator');
      $table->string('location');
      $table->enum('type', array_map(fn($type) => $type->value, $types));
      $table->foreignIdFor(Shift::class)->constrained()->cascadeOnDelete();
      $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
      $table->foreignIdFor(Equipment::class)->constrained()->cascadeOnDelete();

      // crane details
      $table->integer('work_time')->nullable()->comment('in hours');
      $table->integer('delivery_time')->nullable()->comment('in hours');
      $table->text('work_note')->nullable();
      $table->text('delivery_note')->nullable();
      $table->text('internal_moving_note')->nullable();

      // trailer details
      $table->integer('trailer_time')->nullable()->comment('in hours');
      $table->text('trailer_note')->nullable();

      // price details
      $table->integer('price')->default(0)->comment('in IDR');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('logbooks');
  }
};
