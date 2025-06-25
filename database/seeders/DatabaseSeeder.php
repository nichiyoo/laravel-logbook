<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shift;
use App\Models\Vendor;
use App\Enums\RoleType;
use App\Models\Equipment;
use App\Enums\EquipmentType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::factory()->create([
      'name' => 'Administrator',
      'email' => 'admin@example.com',
      'role' => RoleType::ADMIN,
    ]);

    User::factory()->create([
      'name' => 'Manager',
      'email' => 'manager@example.com',
      'role' => RoleType::MANAGER,
    ]);

    User::factory()->create([
      'name' => 'Frontman',
      'email' => 'frontman@example.com',
      'role' => RoleType::FRONTMAN,
    ]);

    $vendors = [
      ['id' => 1, 'name' => 'SPM'],
      ['id' => 2, 'name' => 'NURUL A\'LA'],
      ['id' => 3, 'name' => 'BCK'],
      ['id' => 4, 'name' => 'BKSI'],
      ['id' => 5, 'name' => 'BPI'],
      ['id' => 6, 'name' => 'SWARNA'],
    ];

    foreach ($vendors as $vendor) {
      Vendor::create($vendor);
    }

    $equipments = [
      ['vendor_id' => 1, 'type' => EquipmentType::CRANE, 'name' => 'CRANE CM2', 'code' => 'A8811YA'],
      ['vendor_id' => 1, 'type' => EquipmentType::CRANE, 'name' => 'CRANE CM2', 'code' => 'B9439ES'],
      ['vendor_id' => 1, 'type' => EquipmentType::CRANE, 'name' => 'CRANE 35T', 'code' => 'B9980V'],
      ['vendor_id' => 1, 'type' => EquipmentType::CRANE, 'name' => 'CRANE CM2', 'code' => 'B8168UAl'],
      ['vendor_id' => 2, 'type' => EquipmentType::CRANE, 'name' => 'CRANE CM2', 'code' => 'B9598JZ'],
      ['vendor_id' => 2, 'type' => EquipmentType::CRANE, 'name' => 'CRANE CM2', 'code' => 'B9383FH'],
      ['vendor_id' => 3, 'type' => EquipmentType::TRAILER, 'name' => 'TRAILER KPI', 'code' => 'BCK'],
      ['vendor_id' => 4, 'type' => EquipmentType::TRAILER, 'name' => 'TRAILER KPI', 'code' => 'BKSI'],
      ['vendor_id' => 5, 'type' => EquipmentType::TRAILER, 'name' => 'TRAILER KPI', 'code' => 'BPI'],
      ['vendor_id' => 6, 'type' => EquipmentType::TRAILER, 'name' => 'TRAILER KA', 'code' => 'SWARNA'],
    ];

    foreach ($equipments as $equipment) {
      Equipment::create([
        ...$equipment,
        'price' => random_int(1000, 10000) * 1000,
      ]);
    }

    $shifts = [
      ['label' => 'Shift 1', 'description' => 'Shift pertama pukul 09.00 WIB - 17.00 WIB'],
      ['label' => 'Shift 2', 'description' => 'Shift kedua pukul 17.00 WIB - 01.00 WIB'],
      ['label' => 'Shift 3', 'description' => 'Shift ketiga pukul 01.00 WIB - 09.00 WIB'],
    ];

    foreach ($shifts as $shift) {
      Shift::create($shift);
    }
  }
}
