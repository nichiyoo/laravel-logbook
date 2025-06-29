<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\EfficiencyPlanning;

class EfficiencyPlanningChart extends ChartWidget
{
  protected static ?string $heading = 'Efficiency planning chart';
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
    $months = range(1, 12);

    $data = collect($months)->map(function ($month) use ($year) {
      $date = Carbon::create($year, $month, 1);

      $plannings = EfficiencyPlanning::with('equipment', 'logbooks')
        ->where('year', $date->year)
        ->where('month', $date->month)
        ->get();

      $total = $plannings->sum(function ($planning) {
        return $planning->total * $planning->equipment->price;
      });

      $actual = $plannings->sum(function ($planning) {
        return $planning->actual->price;
      });

      return [
        'month' => $date->format('M'),
        'total_planning' => $total,
        'total_actual' => $actual,
      ];
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
