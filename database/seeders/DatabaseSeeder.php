<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use App\Models\Equipment;
use App\Models\User;
use App\Models\Vendor;
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
      'role' => RoleType::Admin,
    ]);

    User::factory()->create([
      'name' => 'Manager',
      'email' => 'manager@example.com',
      'role' => RoleType::Manager,
    ]);

    User::factory()->create([
      'name' => 'Frontman',
      'email' => 'frontman@example.com',
      'role' => RoleType::Frontman,
    ]);

    /*
    SPM
    - CRANE CM2 (A8811YA)
    - CRANE CM2 (B9439ES)
    - CRANE CM2 (B8168UAl)
    - CRANE CM2 (A5960U)

    NURUL A'LA
    - CRANE 35T (B9980V)
    - CRANE 35T (B9907V)
    - CRANE CM2 (B9598JZ)
    - CRANE CM2 (B9383FH)

    BCK
    - TRAILER KPI-BCK

    BKSI
    - TRAILER KPI-BKSI

    BPI
    - TRAILER KPI-BPI

    SWARNA
    - TRAILER KA-SWARNA
    */

    $vendors = [
      [
        'id' => 1,
        'name' => 'SPM',
      ],
      [
        'id' => 2,
        'name' => 'NURUL A\'LA',
      ],
      [
        'id' => 3,
        'name' => 'BCK',
      ],
      [
        'id' => 4,
        'name' => 'BKSI',
      ],
      [
        'id' => 5,
        'name' => 'BPI',
      ],
      [
        'id' => 6,
        'name' => 'SWARNA',
      ],
    ];

    foreach ($vendors as $vendor) {
      Vendor::create($vendor);
    }

    $equipments = [
      [
        'vendor_id' => 1,
        'name' => 'CRANE CM2',
        'code' => 'A8811YA',
      ],
      [
        'vendor_id' => 1,
        'name' => 'CRANE CM2',
        'code' => 'B9439ES',
      ],
      [
        'vendor_id' => 1,
        'name' => 'CRANE 35T',
        'code' => 'B9980V',
      ],
      [
        'vendor_id' => 1,
        'name' => 'CRANE CM2',
        'code' => 'B8168UAl',
      ],
      [
        'vendor_id' => 2,
        'name' => 'CRANE CM2',
        'code' => 'B9598JZ',
      ],
      [
        'vendor_id' => 2,
        'name' => 'CRANE CM2',
        'code' => 'B9383FH',
      ],
      [
        'vendor_id' => 3,
        'name' => 'TRAILER KPI-BCK',
      ],
      [
        'vendor_id' => 4,
        'name' => 'TRAILER KPI-BKSI',
      ],
      [
        'vendor_id' => 5,
        'name' => 'TRAILER KPI-BPI',
      ],
      [
        'vendor_id' => 6,
        'name' => 'TRAILER KA-SWARNA',
      ],
    ];

    foreach ($equipments as $equipment) {
      Equipment::create($equipment);
    }
  }
}
