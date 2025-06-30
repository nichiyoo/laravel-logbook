<?php

namespace App\Filament\Widgets;

use App\Models\Logbook;
use Flowframe\Trend\Trend;
use App\Enums\EquipmentType;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class CraneUsageChart extends ChartWidget
{
  protected static ?string $heading = 'Crane usage hours chart this year';
  protected static ?string $pollingInterval = null;
  protected static ?int $sort = 1;

  protected function getOptions(): array
  {
    return [
      'elements' => [
        'bar' => [
          'borderWidth' => 0,
        ]
      ],
      'scales' => [
        'y' => [
          'grid' => [
            'display' => true,
          ],
          'ticks' => [
            'stepSize' => 1,
          ],
        ],
        'x' => [
          'grid' => [
            'display' => false,
          ],
        ],
      ],
      'plugins' => [
        'legend' => [
          'display' => true,
        ],
      ],
    ];
  }

  protected function getData(): array
  {
    $start = now()->startOfYear();
    $end = now()->endOfYear();

    $works = Trend::query(Logbook::where('type', EquipmentType::CRANE))
      ->dateColumn('date')
      ->between(start: $start, end: $end)
      ->perMonth()
      ->sum('work_time');

    $deliveries = Trend::query(Logbook::where('type', EquipmentType::CRANE))
      ->dateColumn('date')
      ->between(start: $start, end: $end)
      ->perMonth()
      ->sum('delivery_time');

    $combined = collect(array_map(
      function ($work, $delivery) {
        return (object) [
          'date' => $work->date,
          'aggregate' => $work->aggregate + $delivery->aggregate,
        ];
      },
      $works->toArray(),
      $deliveries->toArray()
    ))->map(fn(object $value) => new TrendValue($value->date, $value->aggregate));

    return [
      'datasets' => [
        [
          'label' => 'Crane usage',
          'data' => $combined->map(fn(TrendValue $value) => $value->aggregate),
          'backgroundColor' => '#2a9d90',
          'borderColor' => '#2a9d90',
        ],
      ],
      'labels' => $combined->map(fn(TrendValue $value) => Carbon::createFromFormat('Y-m', $value->date)->format('M')),
    ];
  }

  protected function getType(): string
  {
    return 'bar';
  }
}
