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
          'stacked' => true,
        ],
        'x' => [
          'grid' => [
            'display' => false,
          ],
          'stacked' => true,
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

    $work = Trend::query(Logbook::where('type', EquipmentType::CRANE))
      ->between(start: $start, end: $end)
      ->perMonth()
      ->sum('work_time');

    $delivery = Trend::query(Logbook::where('type', EquipmentType::CRANE))
      ->between(start: $start, end: $end)
      ->perMonth()
      ->sum('delivery_time');

    return [
      'datasets' => [
        [
          'label' => 'Work time',
          'data' => $work->map(fn(TrendValue $value) => $value->aggregate),
          'backgroundColor' => '#2a9d90',
          'borderColor' => '#2a9d90',
        ],
        [
          'label' => 'Delivery time',
          'data' => $delivery->map(fn(TrendValue $value) => $value->aggregate),
          'backgroundColor' => '#e76e50',
          'borderColor' => '#e76e50',
        ]
      ],
      'labels' => $work->map(fn(TrendValue $value) => Carbon::createFromFormat('Y-m', $value->date)->format('M')),
    ];
  }

  protected function getType(): string
  {
    return 'bar';
  }
}
