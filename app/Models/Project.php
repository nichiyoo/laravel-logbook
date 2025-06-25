<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
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
   * Get the logbooks that belong to the project.
   *
   * @return Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function logbooks(): HasMany
  {
    return $this->hasMany(Logbook::class);
  }
}
