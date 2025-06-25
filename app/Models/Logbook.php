<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array<string, mixed>
   */
  protected $fillable = [
    'date',
    'operator',
    'location',
    'work_time',
    'delivery_time',
    'work_note',
    'delivery_note',
    'internal_moving_note',
    'trailer_time',
    'trailer_note',
    'shift_id',
    'project_id',
    'equipment_id',
    'price',
  ];

  /**
   * Get the shift that owns the logbook.
   *
   * @return Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function shift(): BelongsTo
  {
    return $this->belongsTo(Shift::class);
  }

  /**
   * Get the project that owns the logbook.
   *
   * @return Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * Get the equipment that owns the logbook.
   *
   * @return Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function equipment(): BelongsTo
  {
    return $this->belongsTo(Equipment::class);
  }
}
