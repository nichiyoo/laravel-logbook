<?php

namespace App\Models;

use stdClass;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EfficiencyPlanning extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array<string, mixed>
   */
  protected $fillable = [
    'year',
    'month',
    'planning_week_1',
    'planning_week_2',
    'planning_week_3',
    'planning_week_4',
    'target',
    'equipment_id',
  ];

  /**
   * Get the equipment that owns the efficiency planning.
   */
  public function equipment(): BelongsTo
  {
    return $this->belongsTo(Equipment::class);
  }

  /**
   * Get the sum of planning
   */
  public function total(): Attribute
  {
    return Attribute::make(
      get: fn() => array_sum([
        $this->planning_week_1,
        $this->planning_week_2,
        $this->planning_week_3,
        $this->planning_week_4,
      ])
    );
  }

  /**
   * Relationship to get all logbooks for this efficiency planning period
   */
  public function logbooks()
  {
    $start = Carbon::create($this->year, $this->month, 1)->startOfMonth();
    $month = Carbon::create($this->year, $this->month, 1)->endOfMonth();

    return $this->hasMany(Logbook::class, 'equipment_id', 'equipment_id')
      ->whereBetween('date', [
        $start->toDateString(),
        $month->toDateString()
      ]);
  }

  /**
   * Get the actual weeks of the efficiency planning (using eager loaded data)
   */
  public function getActualAttribute()
  {
    $data = new stdClass();
    $total = 0;

    $year = $this->year;
    $month = $this->month;
    $days = Carbon::create($year, $month)->daysInMonth;

    $maps = [
      'actual_week_1' => [1, 7],
      'actual_week_2' => [8, 14],
      'actual_week_3' => [15, 21],
      'actual_week_4' => [22, $days],
    ];

    foreach ($maps as $key => [$first, $last]) {
      $start = Carbon::create($year, $month, $first)->startOfDay();
      $end = Carbon::create($year, $month, $last)->endOfDay();

      $result = $this->logbooks->filter(function ($logbook) use ($start, $end) {
        $date = Carbon::parse($logbook->date);
        return $date->between($start, $end);
      })->sum(function ($logbook) {
        return array_sum([
          $logbook->work_time,
          $logbook->delivery_time,
          $logbook->trailer_time,
        ]);
      });

      $data->{$key} = $result;
      $total += $result;
    }

    $plannings = array_sum([
      $this->planning_week_1,
      $this->planning_week_2,
      $this->planning_week_3,
      $this->planning_week_4,
    ]);

    $data->total = $total;
    $data->price = $total * $this->equipment->price;

    $data->available = new stdClass();
    $data->available->time = $plannings - $total;
    $data->available->price = $data->available->time * $this->equipment->price;

    return $data;
  }
}
