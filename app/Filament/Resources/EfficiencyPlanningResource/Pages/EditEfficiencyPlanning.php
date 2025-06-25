<?php

namespace App\Filament\Resources\EfficiencyPlanningResource\Pages;

use App\Filament\Resources\EfficiencyPlanningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEfficiencyPlanning extends EditRecord
{
    protected static string $resource = EfficiencyPlanningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
