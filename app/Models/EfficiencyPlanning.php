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
      get: fn() => $this->planning_week_1 + $this->planning_week_2 + $this->planning_week_3 + $this->planning_week_4,
    );
  }

  /**
   * Get the actual weeks of the efficiency planning.
   */
  public function actual(): Attribute
  {
    return Attribute::make(
      get: function () {
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
          $start = Carbon::create($year, $month, $first)->startOfDay()->toDateString();
          $end = Carbon::create($year, $month, $last)->endOfDay()->toDateString();

          $totals = Logbook::query()
            ->where('equipment_id', $this->equipment_id)
            ->whereBetween('date', [$start, $end])
            ->selectRaw('
                COALESCE(SUM(work_time), 0) as work,
                COALESCE(SUM(delivery_time), 0) as delivery,
                COALESCE(SUM(trailer_time), 0) as trailer
            ')
            ->first();

          $sum = $totals->work + $totals->delivery + $totals->trailer;
          $data->{$key} = $sum;
          $total += $sum;
        }

        $plannings = array_sum([
          $this->planning_week_1,
          $this->planning_week_2,
          $this->planning_week_3,
          $this->planning_week_4,
        ]);

        $data->total = $total * $this->equipment->price;
        $data->efficiency_time = $total - $plannings;
        $data->efficiency = $data->efficiency_time * $this->equipment->price;
        return $data;
      },
    );
  }
}
