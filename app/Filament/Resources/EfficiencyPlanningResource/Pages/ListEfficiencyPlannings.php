<?php

namespace App\Filament\Resources\EfficiencyPlanningResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EfficiencyPlanningResource;

class ListEfficiencyPlannings extends ListRecords
{
  protected static string $resource = EfficiencyPlanningResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}
