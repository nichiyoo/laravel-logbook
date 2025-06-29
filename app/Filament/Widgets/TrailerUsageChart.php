<?php

namespace App\Filament\Widgets;

use App\Models\Logbook;
use Flowframe\Trend\Trend;
use App\Enums\EquipmentType;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TrailerUsageChart extends ChartWidget
{
  protected static ?string $heading = 'Trailer usage hour chart this year';
  protected static ?int $sort = 2;

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
          ]
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

    $data = Trend::query(Logbook::where('type', EquipmentType::TRAILER))
      ->between(start: $start, end: $end)
      ->perMonth()
      ->sum('trailer_time');

    return [
      'datasets' => [[
        'label' => 'Work time',
        'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
        'backgroundColor' => '#e8c468',
        'borderColor' => '#e8c468',
      ]],
      'labels' => $data->map(fn(TrendValue $value) => Carbon::createFromFormat('Y-m', $value->date)->format('M')),
    ];
  }

  protected function getType(): string
  {
    return 'bar';
  }
}
