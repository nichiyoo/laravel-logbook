<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\EfficiencyPlanning;

class EfficiencyPlanningChart extends ChartWidget
{
  protected static ?string $heading = 'Efficiency planning chart';
  protected static ?string $pollingInterval = null;
  protected int | string | array $columnSpan = 2;
  protected static ?int $sort = 3;

  protected function getOptions(): array
  {
    return [
      'scales' => [
        'y' => [
          'grid' => [
            'display' => true,
          ],
        ],
        'x' => [
          'grid' => [
            'display' => false,
          ],
        ],
      ],
    ];
  }

  protected function getData(): array
  {
    $year = now()->year;
    $ranges = range(1, 12);

    $plannings = EfficiencyPlanning::with(['equipment', 'logbooks'])
      ->where('year', $year)
      ->whereBetween('month', [1, 12])
      ->get();

    $monthly = $plannings->groupBy('month')->map(function ($monthPlannings, $month) {
      $total = $monthPlannings->sum(function ($planning) {
        return $planning->total * $planning->equipment->price;
      });

      $actual = $monthPlannings->sum(function ($planning) {
        return $planning->actual->price;
      });

      return [
        'month' => Carbon::create(null, $month, 1)->format('M'),
        'total_planning' => $total,
        'total_actual' => $actual,
      ];
    });

    $data = collect($ranges)->map(function ($month) use ($monthly) {
      return $monthly->get($month, [
        'month' => Carbon::create(null, $month, 1)->format('M'),
        'total_planning' => 0,
        'total_actual' => 0,
      ]);
    });

    $labels = $data->pluck('month');
    $plannings = $data->pluck('total_planning');
    $actual = $data->pluck('total_actual');

    return [
      'datasets' => [
        [
          'label' => 'Total planning',
          'data' => $plannings,
          'backgroundColor' => '#2a9d90',
          'borderColor' => '#2a9d90',
        ],
        [
          'label' => 'Total actual',
          'data' => $actual,
          'backgroundColor' => '#e76e50',
          'borderColor' => '#e76e50',
        ]
      ],
      'labels' => $labels,
    ];
  }

  protected function getType(): string
  {
    return 'line';
  }
}
