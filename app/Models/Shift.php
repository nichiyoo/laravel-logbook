<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array<string, mixed>
   */
  protected $fillable = [
    'label',
    'description',
  ];

  /**
   * Get the daily reports that belong to the shift.
   *
   * @return Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function reports(): HasMany
  {
    return $this->hasMany(DailyReport::class);
  }

  /**
   * Get the logbooks that belong to the shift.
   *
   * @return Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function logbooks(): HasMany
  {
    return $this->hasMany(Logbook::class);
  }
}
