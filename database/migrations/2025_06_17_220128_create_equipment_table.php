<?php

use App\Models\Vendor;
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

    Schema::create('equipment', function (Blueprint $table) use ($types) {
      $table->id();
      $table->timestamps();
      $table->string('name');
      $table->string('code')->nullable()->unique();
      $table->string('description')->nullable();
      $table->string('image')->nullable();
      $table->integer('price')->default(0);
      $table->enum('type', array_map(fn($type) => $type->value, $types));
      $table->foreignIdFor(Vendor::class)->constrained()->cascadeOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('equipment');
  }
};
