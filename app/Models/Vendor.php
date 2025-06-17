<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array<string, mixed>
   */
  protected $fillable = [
    'name',
    'description',
  ];

  /**
   * Get the equipment that belongs to the vendor.
   */
  public function equipments(): HasMany
  {
    return $this->hasMany(Equipment::class);
  }
}
