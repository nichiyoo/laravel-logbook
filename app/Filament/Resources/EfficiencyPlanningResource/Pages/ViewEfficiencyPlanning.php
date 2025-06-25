<?php

namespace App\Filament\Resources\EfficiencyPlanningResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\EfficiencyPlanningResource;
use App\Filament\Resources\EfficiencyPlanningResource\Pages;

class ViewEfficiencyPlanning extends ViewRecord
{
  protected static string $resource = EfficiencyPlanningResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\EditAction::make(),
    ];
  }
}
